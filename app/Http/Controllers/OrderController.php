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

        $order = $table->orders()->where('status', 'abierto')->first();

        if (!$order) {
            $order = Order::create([
                'table_id' => $table->id,
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
            ]);
        } else {
            // Si no, agregarlo como un nuevo producto
            $order->products()->attach($product->id, [
                'quantity' => $request->quantity,
                'price_at_time' => $product->price,
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
            return redirect()->route('tables.show', $table->number)->with('error', 'No hay un pedido abierto para esta mesa');
        }

        $order->closed_at = now();

        // Actualizar el estado del pedido a cerrado (pagado)
        $order->status = 'cerrado';
        $order->save();

        return redirect()->route('tables.show', $table->number)->with('success', 'Pedido cerrado y pagado');
    }
}
