<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class TableController extends Controller
{
    //List all active tables
    public function index()
    {
        $tables = Table::where('user_id', auth()->id())
            ->where('active', 1)
            ->get();
        return view('tables.index', compact('tables'));
    }

    // Show a specific table view
    public function show($number)
    {
        // Recover table by number and user_id to ensure it belongs to the user
        $table = Table::where('number', $number)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        if (!$table) {
            abort(403, 'No tienes permiso para acceder a esta mesa');
        }

        // Look for acrtive order
        $order = $table->orders()->open()->first();

        // List all products that belong to the active user
        $products = Product::where('user_id', auth()->user()->id)->active()->get();

        return view('tables.show', compact('table', 'order', 'products', 'table'));
    }

    // Show all tables for the owner
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

        // Search for the highest table number that this user has had
        $maxNumber = Table::where('user_id', $userId)->max('number') ?? 0;

        // Create the new table with the next number
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

        // Check if the table is occupied (status = 1)
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
