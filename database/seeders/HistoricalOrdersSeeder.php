<?php

namespace Database\Seeders;

use App\Models\CashRegister;
use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


// This seeder generates historical orders for a user (stablishment) in the database.
class HistoricalOrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Custom configuration
        $user = User::latest()->first(); // Last created user id (stablishment)
        $daysBack = 365; // Backwards days to generate orders
        $minOrdersPerDay = 2; // Minium orders per day
        $maxOrdersPerDay = 8; // Maxium orders per day
        $minProductsPerOrder = 1; //Minium products per order
        $maxProductsPerOrder = 5; // Maxium products per order

        if (!$user) {
            $this->command->error('Usuario no encontrado');
            return;
        }

        // Check if the user has an employee associated
        $employee = DB::table('employees')->where('user_id', $user->id)->first();
        if (!$employee) {
            $this->command->error('El usuario no tiene un empleado asociado');
            return;
        }

        $this->command->info("Generando datos históricos para el establecimiento: {$user->name}");

        // Obtain tables and products from the user
        $tables = Table::where('user_id', $user->id)->get();
        if ($tables->isEmpty()) {
            $this->command->error('El usuario no tiene mesas');
            return;
        }

        // Verify that the tables exist
        $validTables = [];
        foreach ($tables as $table) {
            if (Table::find($table->id)) {
                $validTables[] = $table;
            }
        }

        if (empty($validTables)) {
            $this->command->error('No hay mesas válidas');
            return;
        }

        $tables = collect($validTables);

        $products = Product::where('user_id', $user->id)->active()->get();
        if ($products->isEmpty()) {
            $this->command->error('El usuario no tiene productos');
            return;
        }

        // Verify that the products exist
        $validProducts = [];
        foreach ($products as $product) {
            if (Product::find($product->id)) {
                $validProducts[] = $product;
            }
        }

        if (empty($validProducts)) {
            $this->command->error('No hay productos válidos');
            return;
        }

        $products = collect($validProducts);

        $this->command->info("Encontradas {$tables->count()} mesas y {$products->count()} productos válidos");

        // Generate orders for each day
        $startDate = Carbon::now()->subDays($daysBack)->startOfDay();
        $endDate = Carbon::now()->subDay()->endOfDay(); // Hasta ayer

        $currentDate = clone $startDate;

        $totalOrders = 0;
        $totalCashRegisters = 0;

        $this->command->info("Generando datos desde {$startDate->format('Y-m-d')} hasta {$endDate->format('Y-m-d')}");

        $progressBar = $this->command->getOutput()->createProgressBar($daysBack);
        $progressBar->start();

        while ($currentDate <= $endDate) {
            // Open the cash register
            $cashOpenTime = clone $currentDate;
            $cashOpenTime->setHour(rand(7, 9))->setMinute(rand(0, 59));

            $cashRegister = $this->createCashRegister($user, $employee, $cashOpenTime);
            $totalCashRegisters++;

            // Random number of orders for this day
            $ordersToday = random_int($minOrdersPerDay, $maxOrdersPerDay);
            $dailyTotal = 0;

            for ($i = 0; $i < $ordersToday; $i++) {
                // Random order creation
                $orderTotal = $this->createRandomOrder(
                    $user,
                    $tables->random(),
                    $products,
                    $currentDate,
                    $minProductsPerOrder,
                    $maxProductsPerOrder,
                    $cashRegister->id,
                    $cashOpenTime
                );

                $dailyTotal += $orderTotal;
                $totalOrders++;
            }

            // Close the cash register at the end of the day
            $cashCloseTime = clone $currentDate;
            $cashCloseTime->setHour(rand(21, 23))->setMinute(rand(0, 59));

            $this->closeCashRegister($cashRegister, $dailyTotal, $cashCloseTime);

            // Advance to the next day
            $currentDate->addDay();
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine(2);
        $this->command->info("Proceso completado: {$totalOrders} pedidos y {$totalCashRegisters} cajas generados en {$daysBack} días");
    }

    private function createCashRegister($user, $employee, $openTime)
    {
        // Initial cash register amount between 50 and 200€
        $openingAmount = rand(50, 200);

        // Create the cash register
        return CashRegister::create([
            'user_id' => $user->id,
            'opening_employee_id' => $employee->id,
            'opening_amount' => $openingAmount,
            'status' => 'closed', // Ya que son históricos
            'opened_at' => $openTime,
            'created_at' => $openTime,
            'updated_at' => $openTime,
        ]);
    }

    private function closeCashRegister($cashRegister, $totalSales, $closeTime)
    {
        // Add a little random variation to the closing (missing or surplus)
        $randomVariation = (rand(-10, 10) / 10); // Betweeen -1€ y +1€
        $realClosingAmount = $cashRegister->opening_amount + $totalSales + $randomVariation;
        $theoreticalClosingAmount = $cashRegister->opening_amount + $totalSales;

        // Update the cash register with closing data
        $cashRegister->update([
            'closing_employee_id' => $cashRegister->opening_employee_id,
            'real_closing_amount' => $realClosingAmount,
            'theoretical_closing_amount' => $theoreticalClosingAmount,
            'difference' => $realClosingAmount - $theoreticalClosingAmount,
            'closed_at' => $closeTime,
            'comments' => $randomVariation != 0 ? 'Diferencia de ' . number_format($randomVariation, 2) . '€' : null,
            'updated_at' => $closeTime,
        ]);

        return $cashRegister;
    }

    private function createRandomOrder($user, $table, $products, $date, $minProducts, $maxProducts, $cashRegisterId, $minTime)
    {
        // Check if the table exists in the database
        $actualTable = Table::find($table->id);
        if (!$actualTable) {
            $this->command->error("La mesa con ID {$table->id} no existe.");
            return 0;
        }

        $employee = DB::table('employees')
            ->where('user_id', $user->id)
            ->first();

        if (!$employee) {
            $this->command->error("No se encontró un empleado para el establecimiento ID: {$user->id}");
            return 0;
        }

        $employeeId = $employee->id;

        // Create random hours after cash register opening
        $openingHour = rand(
            max($minTime->hour, 10),
            min(22, $date->copy()->setHour(23)->hour)
        );
        $openingMinute = rand(0, 59);

        $createdAt = clone $date;
        $createdAt->setHour($openingHour)->setMinute($openingMinute)->setSecond(0);

        // Order is closed between 15 minutes and 2 hours after
        $closedAt = clone $createdAt;
        $closedAt->addMinutes(rand(15, 120));

        try {
            // Create the order
            $orderId = DB::table('orders')->insertGetId([
                'table_id' => $table->id,
                'user_id' => $user->id,
                'employee_id' => $employeeId,
                'cash_register_id' => $cashRegisterId, // Asociar a la caja
                'status' => 'cerrado',
                'total_price' => 0,
                'created_at' => $createdAt,
                'updated_at' => $closedAt,
                'closed_at' => $closedAt,
            ]);

            // Add random products to the order
            $productsToAdd = rand($minProducts, min($maxProducts, $products->count()));
            $selectedProducts = $products->where('active', true)->random(max(1, $productsToAdd));
            $totalPrice = 0;

            foreach ($selectedProducts as $product) {
                // Check if the product exists
                if (!Product::find($product->id)) {
                    continue;
                }

                $quantity = rand(1, 3);
                $priceAtTime = $product->price;
                $subtotal = $quantity * $priceAtTime;

                // Add product to the order
                DB::table('order_product')->insert([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_at_time' => $priceAtTime,
                    'created_at' => $createdAt,
                    'updated_at' => $closedAt,
                ]);

                $totalPrice += $subtotal;
            }

            // Update the total price
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['total_price' => $totalPrice]);

            return $totalPrice;

        } catch (\Exception $e) {
            $this->command->error("Error al crear el pedido: " . $e->getMessage());
            return 0;
        }
    }
}
