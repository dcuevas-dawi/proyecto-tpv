<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::where('user_id', auth()->user()->id)->get();
        return view('tables.index', compact('tables'));
    }

    public function select($id)
    {
        $table = Table::find($id);

        if ($table) {
            return redirect()->route('orders.create', ['table_id' => $table->id]);
        }

        return back()->with('error', 'La mesa no existe.');
    }
}
