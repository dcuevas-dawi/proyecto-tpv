<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Once we have created the owner, we use this controller to create other users
class EmployeeLoginController extends Controller
{

    // Show the login form for employees
    public function showLoginForm()
    {
        $employees = Employee::where('user_id', auth()->user()->id)->get();
        return view('employee.login', compact('employees'));
    }

    // Process the login request
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'employee_pin' => 'required',
        ], [
            'employee_id.required' => 'Debe seleccionar un empleado.',
            'employee_id.exists' => 'El empleado seleccionado no existe.',
            'employee_pin.required' => 'El PIN es obligatorio.',
        ]);

        // Find the employee by ID
        $employee = Employee::find($request->employee_id);

        // Check if the employee exists and if the PIN matches
        if ($employee && Hash::check($request->employee_pin, $employee->pin)) {
            session([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'employee_role' => $employee->role,
            ]);

            return redirect()->intended('/menu');
        }

        return back()->withErrors(['employee_pin' => 'PIN incorrecto.']);
    }

    // Logout the employee
    public function logout()
    {
        session()->forget(['employee_id', 'employee_name', 'employee_role']);
        return redirect()->route('menu')->with('status', 'Empleado deslogueado');
    }

    // Show the form to create a new employee
    public function create()
    {
        // Check access permissions
        if (session('employee_role') != 1) {
            abort(403, 'Acceso no autorizado');
        }

        // Check if there is already an employee with role=1 (owner)
        $employees = Employee::where('user_id', Auth::id())->get();

        return view('employee.create', compact('employees'));
    }

    // Store a new employee
    public function store(Request $request)
    {
        try {
            // Validate the request
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

            // Create the employee
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

    // Check if the employee name already exists
    function checkUser($attribute, $value, $fail) {

        if (Employee::where('user_id', Auth::id())
            ->where('name', $value)
            ->exists()) {
            $fail('Ya existe un empleado con este nombre.');
        }
    }


}
