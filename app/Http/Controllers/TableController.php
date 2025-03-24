<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::where('user_id', auth()->id())
            ->where('active', 1)
            ->get();
        return view('tables.index', compact('tables'));
    }

    public function show($number)
    {
        // Buscar la mesa
        $table = Table::where('number', $number)
            ->where('user_id', auth()->user()->id) // Asegura que la mesa pertenece al establecimiento del usuario
            ->firstOrFail();

        // Si no se encuentra la mesa o la mesa no pertenece al establecimiento, aborta con error 403
        if (!$table) {
            abort(403, 'No tienes permiso para acceder a esta mesa');
        }

        // Buscar el pedido abierto de la mesa (si existe)
        $order = $table->orders()->open()->first();

        // Si no hay un pedido abierto, podemos crear uno nuevo


        // Obtener todos los productos disponibles para agregar al pedido
        $products = Product::where('user_id', auth()->user()->id)->get();

        // Retornar la vista con la mesa, el pedido y los productos
        return view('tables.show', compact('table', 'order', 'products', 'table'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer',
        ]);

        // Buscar si ya existía una mesa con ese número, aunque esté inactiva
        $existingTable = Table::where('number', $request->number)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingTable) {
            // Si existe, actualizamos el estado a activa
            $existingTable->active = 1;
            $existingTable->save();
        } else {
            // Si no existía, la creamos
            Table::create([
                'user_id' => auth()->id(),
                'number' => $request->number,
                'active' => 1,
                'status' => 0,
            ]);
        }

        return redirect()->route('tables.index')->with('success', 'Mesa guardada correctamente.');
    }

    public function destroy($id)
    {
        $table = Table::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $table->active = 0;
        $table->save();

        return redirect()->route('tables.index')->with('success', 'Mesa desactivada correctamente.');
    }

}
