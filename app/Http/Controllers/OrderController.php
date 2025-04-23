<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\Request;
use App\Models\CashRegister;

class OrderController extends Controller
{
    // Opening a new order for a table
    public function create($tableId)
    {
        $table = Table::find($tableId);

        if (!$table) {
            return redirect()->back()->with('error', 'La mesa no existe.');
        }

        // Check if there is a cash register open
        $openCashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$openCashRegister) {
            return redirect()->route('tables.show', $table->number)
                ->with('error', 'Debe abrir la caja antes de crear un pedido.');
        }

        // Check if there is already an open order for this table
        $existingOrder = $table->orders()->where('status', 'open')->first();

        // If there is no existing order, create a new one
        if (!$existingOrder) {
            // Change the table status to "occupied" only if there is no open order
            $table->status = 1; // 1 = ocupada
            $table->save();

            // Create a new order
            Order::create([
                'table_id' => $table->id,
                'user_id' => auth()->id(),
                'status' => 'abierto',
                'total_price' => 0,
                'cash_register_id' => $openCashRegister->id,
            ]);

            return redirect()->route('tables.show', ['number' => $table->number])
                ->with('success', 'Nuevo pedido creado');
        }

        // If, for any reason, an order exists, we can redirect to the table view
        return redirect()->route('tables.show', ['number' => $table->number]);
    }

    // Show order details
    public function edit($orderId)
    {
        $order = Order::findOrFail($orderId);
        $products = Product::where('user_id', Auth::id())->active()->get();
        $table = $order->table;

        return view('tables.show', compact('order', 'products', 'table'));
    }

    public function addProduct(Request $request, $tableId)
    {
        // Check product and quantity
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Obtain the table, the associated order and the product
        $table = Table::findOrFail($tableId);
        $order = $table->orders()->open()->first();
        $product = Product::findOrFail($request->product_id);

        // Check if the product is in the current order
        $existingProduct = $order->products()->where('product_id', $product->id)->first();

        if ($existingProduct) {
            // If the product is already in the order, update the quantity
            $order->products()->updateExistingPivot($product->id, [
                'quantity' => $existingProduct->pivot->quantity + $request->quantity,
                'price_at_time' => $product->price,
                'updated_at' => now(),
            ]);
        } else {
            // If not, add it as a new product
            $order->products()->attach($product->id, [
                'quantity' => $request->quantity,
                'price_at_time' => $product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update the total price of the order
        $order->total_price = $order->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->price_at_time;
        });
        $order->save();

        return redirect()->route('tables.show', $table->number)->with('success', 'Producto añadido al pedido');
    }

    public function closeOrder(Request $request, $tableId)
    {
        // Obtain the table and the associated order
        $table = Table::findOrFail($tableId);
        $order = $table->orders()->open()->first();

        // If not open order, return error
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No hay un pedido abierto para esta mesa'
            ]);
        }

        // Taable is now free
        $table->status = 0; // 0 = libre
        $table->save();

        $order->closed_at = now();

        // Update the order status to closed (cerrado)
        $order->status = 'cerrado';
        $order->employee_id = session('employee_id');
        $order->save();

        // Return JSON response with the ticket URL
        return response()->json([
            'success' => true,
            'message' => 'Pedido cerrado correctamente',
            'ticket_url' => route('orders.print', $order->id)
        ]);
    }

    // Update product quantity in order
    public function updateQuantity(Request $request, $orderId, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($orderId);

        if (!$order->products()->where('product_id', $productId)->exists()) {
            return redirect()->back()->with('error', 'El producto no está en el pedido');
        }

        $order->products()->updateExistingPivot($productId, [
            'quantity' => $request->quantity,
            'updated_at' => now(),
        ]);

        $order->total_price = $order->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->price_at_time;
        });
        $order->save();

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }

    // Remove product from order
    public function removeProduct($orderId, $productId)
    {
        $order = Order::findOrFail($orderId);

        if (!$order->products()->where('product_id', $productId)->exists()) {
            return redirect()->back()->with('error', 'El producto no está en el pedido');
        }

        $order->products()->detach($productId);

        $order->total_price = $order->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->price_at_time;
        });
        $order->save();

        return redirect()->back()->with('success', 'Producto eliminado del pedido');
    }

    // Print ticket for closed order
    public function printTicket($orderId)
    {
        $order = Order::findOrFail($orderId);
        $stablishmentDetails = auth()->user()->stablishmentDetails;

        if ($order->status !== 'cerrado') {
            return redirect()->back()->with('error', 'Solo se pueden imprimir tickets de pedidos cerrados');
        }

        return view('orders.ticket', compact('order', 'stablishmentDetails'));
    }

    // Show order history for now
    public function history()
    {
        $today = now()->format('Y-m-d');

        $orders = Order::where('status', 'cerrado')
            ->whereDate('closed_at', $today)
            ->latest('closed_at')
            ->get();

        return view('orders.history', compact('orders'));
    }

    // Get order history by date range
    public function getOrdersByDate(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date') ?? $start_date;

        $orders = Order::where('status', 'cerrado')
            ->whereDate('closed_at', '>=', $start_date)
            ->whereDate('closed_at', '<=', $end_date)
            ->latest('closed_at')
            ->get();

        return view('orders.history', compact('orders', 'start_date', 'end_date'));
    }

    // View ticket for an order
    public function viewTicket($orderId)
    {
        $order = Order::with(['products', 'table', 'employee'])->findOrFail($orderId);
        return view('orders.view-ticket', compact('order'));
    }
}
