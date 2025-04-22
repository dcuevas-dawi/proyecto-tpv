<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashRegisterController extends Controller
{
    // Mostrar formulario para abrir caja
    public function openForm()
    {
        // Verificar si ya hay una caja abierta
        $existingOpenCashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existingOpenCashRegister) {
            return redirect()->route('cash-register.history')->with('error', 'Ya hay una caja abierta');
        }

        return view('cash_register.open');
    }

    // Procesar apertura de caja
    public function open(Request $request)
    {
        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
        ]);

        // Verificar si ya hay una caja abierta
        $existingOpenCashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existingOpenCashRegister) {
            return redirect()->route('cash-register.history')->with('error', 'Ya hay una caja abierta');
        }

        // Crear la apertura de caja
        CashRegister::create([
            'user_id' => auth()->id(),
            'opening_employee_id' => session('employee_id'),
            'opened_at' => now(),
            'opening_amount' => $request->opening_amount,
            'status' => 'open',
        ]);

        return redirect()->route('cash-register.history')->with('success', 'Caja abierta correctamente');
    }

    // Mostrar formulario para cerrar caja
    public function closeForm()
    {
        // Obtener la caja abierta actual
        $cashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$cashRegister) {
            return redirect()->route('cash-register.history')->with('error', 'No hay ninguna caja abierta');
        }

        // Verificar si hay pedidos abiertos
        $openOrders = Order::where('status', '!=', 'cerrado')
            ->where('cash_register_id', $cashRegister->id)
            ->count();

        if ($openOrders > 0) {
            return redirect()->route('cash-register.history')->with('error', 'No se puede cerrar la caja mientras hay pedidos abiertos');
        }

        // Calcular el monto teórico de cierre
        $totalSales = Order::where('cash_register_id', $cashRegister->id)
            ->where('status', 'cerrado')
            ->sum('total_price');

        $theoreticalClosingAmount = $cashRegister->opening_amount + $totalSales;

        return view('cash_register.close', compact('cashRegister', 'theoreticalClosingAmount', 'totalSales'));
    }

    // Procesar cierre de caja
    public function close(Request $request)
    {
        $request->validate([
            'real_closing_amount' => 'required|numeric|min:0',
            'comments' => 'nullable|string',
        ]);

        // Obtener la caja abierta actual
        $cashRegister = CashRegister::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$cashRegister) {
            return redirect()->route('cash-register.history')->with('error', 'No hay ninguna caja abierta');
        }

        // Verificar si hay pedidos abiertos
        $openOrders = Order::where('status', '!=', 'cerrado')
            ->where('cash_register_id', $cashRegister->id)
            ->count();

        if ($openOrders > 0) {
            return redirect()->route('cash-register.history')->with('error', 'No se puede cerrar la caja mientras hay pedidos abiertos');
        }

        // Calcular el monto teórico de cierre
        $totalSales = Order::where('cash_register_id', $cashRegister->id)
            ->where('status', 'cerrado')
            ->sum('total_price');

        $theoreticalClosingAmount = $cashRegister->opening_amount + $totalSales;
        $difference = $request->real_closing_amount - $theoreticalClosingAmount;

        // Actualizar la caja con los datos de cierre
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

    // Listar historial de cajas
    public function history(Request $request)
    {
        $query = CashRegister::where('user_id', auth()->id());

        // Establecer fechas predeterminadas si no se proporcionan
        $startDate = $request->start_date ?? Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        // Extender la fecha final para incluir la madrugada del siguiente día (hasta las 6 AM)
        $endDatePlus = Carbon::parse($endDate)->addDay()->format('Y-m-d');

        // Aplicar filtro de fechas, considerando como "día de trabajo" desde las 06:00 hasta las 06:00 del día siguiente
        $query->where(function($q) use ($startDate, $endDatePlus) {
            // Registros que se abren en el rango normal
            $q->whereBetween('opened_at', [
                $startDate . ' 06:00:00',
                $endDatePlus . ' 05:59:59'
            ]);
        });

        // Ordenar y paginar
        $cashRegisters = $query->orderBy('opened_at', 'desc')
            ->paginate(15);

        return view('cash_register.history', [
            'cashRegisters' => $cashRegisters,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
}
