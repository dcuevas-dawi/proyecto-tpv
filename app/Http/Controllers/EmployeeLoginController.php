<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeLoginController extends Controller
{

    public function showLoginForm()
    {
        $employees = Employee::all(); // Traemos todos los empleados
        return view('employee.login', compact('employees'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'employee_pin' => 'required',
        ]);

        $employee = Employee::find($request->employee_id);

        if ($employee && Hash::check($request->employee_pin, $employee->pin)) {
            session([
                'employee_name' => $employee->name,
                'employee_role' => $employee->role,
            ]);

            return redirect()->intended('/menu');
        }

        return back()->withErrors(['employee_pin' => 'PIN incorrecto.']);
    }

    public function logout()
    {
        session()->forget(['employee_id', 'employee_name', 'employee_role']);
        return redirect()->route('menu')->with('status', 'Empleado deslogueado');
    }

    public function menu()
    {
        if (Auth::user()->employees()->count() === 0) {
            return redirect()->route('employee.create.owner');
        }

        return view('menu');
    }

    public function create()
    {
        return view('employee.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'required|string|min:4|max:10',
            'role' => 'required|integer|in:2,3', // 2 = Encargado, 3 = Empleado
        ]);

        Employee::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'pin' => bcrypt($request->pin),
            'role' => $request->role,
        ]);

        return redirect()->route('menu')->with('success', 'Empleado creado correctamente.');
    }

}
