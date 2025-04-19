<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoricalOrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Customizable configuration
        $user = User::latest()->first(); // Last created user id
        $daysBack = 30; // Number of days back to generate orders
        $minOrdersPerDay = 2; // Minimum orders per day
        $maxOrdersPerDay = 8; // Maximum orders per day
        $minProductsPerOrder = 1; // Minimum products per order
        $maxProductsPerOrder = 5; // Maximum products per order

        if (!$user) {
            $this->command->error('User not found');
            return;
        }

        // Verify that exists as employee
        $employee = User::where('id', $user->id)->first();
        if (!$employee) {
            $this->command->error('User does not exist as employee');
            return;
        }

        $this->command->info("Generating historical data for establishment: {$user->name}");

        // Get tables and products from the user
        $tables = Table::where('user_id', $user->id)->get();
        if ($tables->isEmpty()) {
            $this->command->error('User has no tables');
            return;
        }

        // Verify that tables actually exist
        $validTables = [];
        foreach ($tables as $table) {
            if (Table::find($table->id)) {
                $validTables[] = $table;
            }
        }

        if (empty($validTables)) {
            $this->command->error('No valid tables');
            return;
        }

        $tables = collect($validTables);

        $products = Product::where('user_id', $user->id)->active()->get();
        if ($products->isEmpty()) {
            $this->command->error('User has no products');
            return;
        }

        // Verify that products actually exist
        $validProducts = [];
        foreach ($products as $product) {
            if (Product::find($product->id)) {
                $validProducts[] = $product;
            }
        }

        if (empty($validProducts)) {
            $this->command->error('No valid products');
            return;
        }

        $products = collect($validProducts);

        $this->command->info("Found {$tables->count()} tables and {$products->count()} valid products");

        // Generate orders for each day
        $startDate = Carbon::now()->subDays($daysBack)->startOfDay();
        $endDate = Carbon::now()->subDay()->endOfDay(); // Until yesterday

        $currentDate = clone $startDate;

        $totalOrders = 0;

        $this->command->info("Generating orders from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");

        $progressBar = $this->command->getOutput()->createProgressBar($daysBack);
        $progressBar->start();

        while ($currentDate <= $endDate) {
            // Random number of orders for this day
            $ordersToday = random_int($minOrdersPerDay, $maxOrdersPerDay);

            for ($i = 0; $i < $ordersToday; $i++) {
                // Create random order
                $this->createRandomOrder(
                    $user,
                    $tables->random(),
                    $products,
                    $currentDate,
                    $minProductsPerOrder,
                    $maxProductsPerOrder
                );

                $totalOrders++;
            }

            // Move to next day
            $currentDate->addDay();
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine(2);
        $this->command->info("Process completed: {$totalOrders} orders generated in {$daysBack} days");
    }

    private function createRandomOrder($user, $table, $products, $date, $minProducts, $maxProducts)
    {
        // Check that the table exists in the database
        $actualTable = Table::find($table->id);
        if (!$actualTable) {
            $this->command->error("Table with ID {$table->id} does not exist.");
            return;
        }

        $employee = DB::table('employees')
            ->where('user_id', $user->id)
            ->first();

        // If we don't find an employee, we can show an error or use an alternative
        if (!$employee) {
            $this->command->error("No employee found for establishment ID: {$user->id}");
            $this->command->line("Verify that there are registered employees for this establishment");
            return; // Exit the function without creating the order
        }

        $employeeId = $employee->id;

        // Create random hours between 8:00 am and 11:00 pm
        $openingHour = rand(8, 23);
        $openingMinute = rand(0, 59);

        $createdAt = clone $date;
        $createdAt->setHour($openingHour)->setMinute($openingMinute)->setSecond(0);

        // The order is closed between 15 minutes and 3 hours later
        $closedAt = clone $createdAt;
        $closedAt->addMinutes(rand(15, 180));

        try {
            // Create the order directly with DB to see exactly which columns you need
            $orderId = DB::table('orders')->insertGetId([
                'table_id' => $table->id,
                'user_id' => $user->id,
                'employee_id' => $employeeId,
                'status' => 'cerrado',
                'total_price' => 0,
                'created_at' => $createdAt,
                'updated_at' => $closedAt,
                'closed_at' => $closedAt,
            ]);

            // Add random products to the order
            $productsToAdd = min($maxProducts, $products->count());
            if ($productsToAdd < $minProducts) {
                $productsToAdd = $products->count();
            }

            $selectedProducts = $products->where('active', true)->random(max(1, $productsToAdd));
            $totalPrice = 0;

            foreach ($selectedProducts as $product) {
                // Verify that the product exists
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

        } catch (\Exception $e) {
            $this->command->error("Error creating the order: " . $e->getMessage());
            // Print details for debugging
            $this->command->line("Table ID: {$table->id}");
            $this->command->line("User ID: {$user->id}");
            $this->command->line("Employee ID: {$employeeId}");
        }
    }
}
