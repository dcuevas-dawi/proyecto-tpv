<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashRegisterController extends Controller
{
    // Show form to open cash register
    public function openForm()
    {
        // Check access permissions
        if (session('employee_role') != 1 && session('employee_role') != 2) {
            abort(403, 'Acceso no autorizado');
        }

        // Check if there is already an open cash register
        $existingOpenCashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existingOpenCashRegister) {
            return redirect()->route('cash-register.history')->with('error', 'Ya hay una caja abierta');
        }

        return view('cash_register.open');
    }

    // Process open cash register
    public function open(Request $request)
    {
        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
        ]);

        // Check if there is already an open cash register
        $existingOpenCashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existingOpenCashRegister) {
            return redirect()->route('cash-register.history')->with('error', 'Ya hay una caja abierta');
        }

        // Create a new cash register
        CashRegister::create([
            'user_id' => auth()->id(),
            'opening_employee_id' => session('employee_id'),
            'opened_at' => now(),
            'opening_amount' => $request->opening_amount,
            'status' => 'open',
        ]);

        return redirect()->route('cash-register.history')->with('success', 'Caja abierta correctamente');
    }

    // Show form to close cash register
    public function closeForm()
    {
        // Check access permissions
        if (session('employee_role') != 1 && session('employee_role') != 2) {
            abort(403, 'Acceso no autorizado');
        }

        // Get the current open cash register
        $cashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$cashRegister) {
            return redirect()->route('cash-register.history')->with('error', 'No hay ninguna caja abierta');
        }

        // Check if there are open orders
        $openOrders = Order::where('status', '!=', 'cerrado')
            ->where('cash_register_id', $cashRegister->id)
            ->count();

        if ($openOrders > 0) {
            return redirect()->route('cash-register.history')->with('error', 'No se puede cerrar la caja mientras hay pedidos abiertos');
        }

        // Calculate the theoretical closing amount
        $totalSales = Order::where('cash_register_id', $cashRegister->id)
            ->where('status', 'cerrado')
            ->sum('total_price');

        $theoreticalClosingAmount = $cashRegister->opening_amount + $totalSales;

        return view('cash_register.close', compact('cashRegister', 'theoreticalClosingAmount', 'totalSales'));
    }

    // Process close cash register
    public function close(Request $request)
    {
        $request->validate([
            'real_closing_amount' => 'required|numeric|min:0',
            'comments' => 'nullable|string',
        ]);

        // Obtain the current open cash register
        $cashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$cashRegister) {
            return redirect()->route('cash-register.history')->with('error', 'No hay ninguna caja abierta');
        }

        // Check if there are open orders
        $openOrders = Order::where('status', '!=', 'cerrado')
            ->where('cash_register_id', $cashRegister->id)
            ->count();

        if ($openOrders > 0) {
            return redirect()->route('cash-register.history')->with('error', 'No se puede cerrar la caja mientras hay pedidos abiertos');
        }

        // Calculate the theoretical closing amount
        $totalSales = Order::where('cash_register_id', $cashRegister->id)
            ->where('status', 'cerrado')
            ->sum('total_price');

        $theoreticalClosingAmount = $cashRegister->opening_amount + $totalSales;
        $difference = $request->real_closing_amount - $theoreticalClosingAmount;

        // Update the cash register with closing data
        $cashRegister->update([
            'closing_employee_id' => session('employee_id'),
            'closed_at' => now(),
            'real_closing_amount' => $request->real_closing_amount,
            'theoretical_closing_amount' => $theoreticalClosingAmount,
            'difference' => $difference,
            'comments' => $request->comments,
            'status' => 'closed'
        ]);

        return redirect()->route('cash-register.history')->with('success', 'Caja cerrada correctamente');
    }

    // List cash register history
    public function history(Request $request)
    {
        // Check access permissions
        if (session('employee_role') != 1 && session('employee_role') != 2) {
            abort(403, 'Acceso no autorizado');
        }

        $query = CashRegister::where('user_id', auth()->id());

        // Establish default dates if not provided
        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        if($startDate > $endDate) {
            return redirect()->route('cash-register.history')->with('error', 'La fecha inicial no puede ser superior a la fecha final');
        }

        // Extend the end date to include the next day (till 05:59:59)
        $endDatePlus = Carbon::parse($endDate)->addDay()->format('Y-m-d');

        // Apply date filter, considering "working day" from 06:00 to 06:00 the next day
        $query->where(function($q) use ($startDate, $endDatePlus) {
            // Registros que se abren en el rango normal
            $q->whereBetween('opened_at', [
                $startDate . ' 06:00:00',
                $endDatePlus . ' 05:59:59'
            ]);
        });

        // Order and paginate results
        $cashRegisters = $query->orderBy('opened_at', 'desc')
            ->paginate(15);

        return view('cash_register.history', [
            'cashRegisters' => $cashRegisters,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
}
