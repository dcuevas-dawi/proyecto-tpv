<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create($tableId)
    {
        $table = Table::find($tableId);

        if (!$table) {
            return redirect()->back()->with('error', 'La mesa no existe.');
        }

        // Cambiar el estado de la mesa a "ocupada"
        $table->status = 1; // 1 = ocupada
        $table->save();

        $order = $table->orders()->where('status', 'abierto')->first();

        if (!$order) {
            Order::create([
                'table_id' => $table->id,
                'user_id' => auth()->id(),
                'status' => 'abierto',
                'total_price' => 0,
            ]);
        }

        return redirect()->route('tables.show', ['number' => $table->number])->with('success', 'Nuevo pedido creado');
    }

    public function edit($orderId)
    {
        $order = Order::findOrFail($orderId);
        $products = Product::where('user_id', auth()->user()->id)->get();
        $table = $order->table;

        return view('tables.show', compact('order', 'products', 'table'));
    }

    public function addProduct(Request $request, $tableId)
    {
        // Validar el producto y la cantidad
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Obtener la mesa y el pedido asociado
        $table = Table::findOrFail($tableId);
        $order = $table->orders()->open()->first();

        // Si no hay pedido abierto, lo creamos
        if (!$order) {
            $order = Order::create([
                'status' => 'abierto',
                'table_id' => $table->id,
                'total_price' => 0,
            ]);
        }

        // Obtener el producto
        $product = Product::findOrFail($request->product_id);

        // Verificar si el producto ya está en el pedido
        $existingProduct = $order->products()->where('product_id', $product->id)->first();

        if ($existingProduct) {
            // Si el producto ya está, actualizar la cantidad
            $order->products()->updateExistingPivot($product->id, [
                'quantity' => $existingProduct->pivot->quantity + $request->quantity,
                'price_at_time' => $product->price,
                'updated_at' => now(),
            ]);
        } else {
            // Si no, agregarlo como un nuevo producto
            $order->products()->attach($product->id, [
                'quantity' => $request->quantity,
                'price_at_time' => $product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Actualizar el precio total del pedido
        $order->total_price = $order->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->price_at_time;
        });
        $order->save();

        return redirect()->route('tables.show', $table->number)->with('success', 'Producto añadido al pedido');
    }

    public function closeOrder(Request $request, $tableId)
    {
        // Obtener la mesa y el pedido asociado
        $table = Table::findOrFail($tableId);
        $order = $table->orders()->open()->first();

        // Si no hay pedido abierto, devolver error
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No hay un pedido abierto para esta mesa'
            ]);
        }

        // Cambiar el estado de la mesa a "libre"
        $table->status = 0; // 0 = libre
        $table->save();

        $order->closed_at = now();

        // Actualizar el estado del pedido a cerrado (pagado)
        $order->status = 'cerrado';
        $order->employee_id = session('employee_id');
        $order->save();

        // Devolver respuesta JSON con la URL del ticket
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

    // Get orders by date range
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
}
