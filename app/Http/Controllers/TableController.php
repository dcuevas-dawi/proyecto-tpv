<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class TableController extends Controller
{
    //Lis all tables
    public function index()
    {
        $tables = Table::where('user_id', auth()->id())
            ->where('active', 1)
            ->get();
        return view('tables.index', compact('tables'));
    }

    // Show a specific table
    public function show($number)
    {
        $table = Table::where('number', $number)
            ->where('user_id', auth()->user()->id) // Asegura que la mesa pertenece al establecimiento del usuario
            ->firstOrFail();

        if (!$table) {
            abort(403, 'No tienes permiso para acceder a esta mesa');
        }

        // Look for acrtive order
        $order = $table->orders()->open()->first();

        // List all products for the user
        $products = Product::where('user_id', auth()->user()->id)->get();

        return view('tables.show', compact('table', 'order', 'products', 'table'));
    }

    // Show all tables for owner
    public function manage()
    {
        if (session('employee_role') != 1) {
            abort(403, 'Acceso no autorizado');
        }

        $tables = Table::where('user_id', auth()->id())
            ->get();
        return view('tables.manage', compact('tables'));
    }

    // Add a new table (being owner)
    public function add(Request $request)
    {
        $userId = auth()->id();

        // Buscar el número más alto de mesa que haya tenido este usuario
        $maxNumber = Table::where('user_id', $userId)->max('number') ?? 0;

        // Crear la nueva mesa con el siguiente número
        Table::create([
            'user_id' => $userId,
            'number' => $maxNumber + 1
        ]);

        return redirect()->route('tables.manage')->with('success', 'Nueva mesa añadida correctamente.');
    }

    // Deactivate a table (being owner)
    public function deactivate($id)
    {
        $table = Table::findOrFail($id);

        // Verificar si la mesa está ocupada (status = 1)
        if ($table->status == 1) {
            return redirect()->route('tables.manage')->with('error', 'No se puede desactivar una mesa con un pedido abierto.');
        }

        $table->active = 0;
        $table->save();

        return redirect()->route('tables.manage')->with('success', 'Mesa desactivada correctamente.');
    }


    // Activate a table (being owner)
    public function activate($id)
    {
        $table = Table::findOrFail($id);
        $table->active = 1;
        $table->save();

        return redirect()->route('tables.manage')->with('success', 'Mesa activada correctamente.');
    }

}
