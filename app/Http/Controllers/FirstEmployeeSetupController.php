<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FirstEmployeeSetupController extends Controller  // Clase para crear el primer usuario: el Dueño, es algo diferente al de crear un empleado
{
    public function showOwnerForm()
    {
        return view('employee.create_owner');
    }

    public function storeOwner(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'required|digits:6|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'pin.required' => 'El PIN es obligatorio.',
            'pin.digits' => 'El PIN debe tener exactamente 6 dígitos.',
            'pin.confirmed' => 'La confirmación del PIN no coincide.',
        ]);

        $employee = Employee::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'pin' => bcrypt($request->pin),
            'role' => 1, // 1 = Owner, El primer empleado siempre será un dueño
        ]);

        session([
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'employee_role' => $employee->role,
        ]);

        return redirect()->route('menu')->with('success', 'Dueño creado correctamente.');
    }

}
