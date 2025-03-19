<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FirstEmployeeSetupController extends Controller
{
    public function showOwnerForm()
    {
        return view('employee.create_owner');
    }

    public function storeOwner(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'required|string|min:4|max:10|confirmed',
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
