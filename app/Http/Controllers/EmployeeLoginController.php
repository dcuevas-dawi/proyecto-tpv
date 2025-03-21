<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeLoginController extends Controller  // Una vez tenemos creado el dueño, utilizamos este ontrolador para crear otros usuarios
{

    public function showLoginForm()
    {
        $employees = Employee::all();
        return view('employee.login', compact('employees'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'employee_pin' => 'required',
        ], [
            'employee_id.required' => 'Debe seleccionar un empleado.',
            'employee_id.exists' => 'El empleado seleccionado no existe.',
            'employee_pin.required' => 'El PIN es obligatorio.',
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
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        $this->checkUser($attribute, $value, $fail);
                    },
                ],
                'pin' => 'required|digits:6',
                'role' => 'required|integer|in:1,2,3', // 1 = Owner, 2 = Manager, 3 = Employee
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',
                'pin.required' => 'El PIN es obligatorio.',
                'pin.digits' => 'El PIN debe tener exactamente 6 dígitos.',
                'role.required' => 'El rol es obligatorio.',
                'role.in' => 'El rol seleccionado no es válido.',
            ]);

            Employee::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'pin' => bcrypt($request->pin),
                'role' => $request->role,
            ]);

            return redirect()->route('employee.create')->with('success', 'Empleado creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el empleado: ' . $e->getMessage());
        }
    }

    function checkUser($attribute, $value, $fail) {  // Función para comprobar si ya existe un usuario con el mismo nombre
        // Check if an employee with this name already exists for the current user
        if (Employee::where('user_id', Auth::id())
            ->where('name', $value)
            ->exists()) {
            $fail('Ya existe un empleado con este nombre.');
        }
    }


}
