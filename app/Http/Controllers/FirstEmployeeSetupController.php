<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Class to create the first user: the Owner, it's a bit different from creating an employee
class FirstEmployeeSetupController extends Controller
{
    // Show the form to create the first employee (owner)
    public function showOwnerForm()
    {
        // Chesk if there is already an employee with role=1 (owner)
        $ownerExists = Employee::where('user_id', auth()->id())
            ->where('role', 1)
            ->exists();

        // If an owner already exists, redirect with an error message
        if (session('employee_role') != 1) {
            abort(403, 'Acceso no autorizado');
        }

        return view('employee.create_owner');
    }

    // Process the form to create the first employee (owner)
    public function storeOwner(Request $request)
    {
        // Validate the request
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

        // Create the first employee (owner)
        $employee = Employee::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'pin' => bcrypt($request->pin),
            'role' => 1, // 1 = Owner, First employee always be owner
        ]);

        // Log the owner in automatically
        session([
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'employee_role' => $employee->role,
        ]);

        return redirect()->route('menu')->with('success', 'Dueño creado correctamente.');
    }

}
