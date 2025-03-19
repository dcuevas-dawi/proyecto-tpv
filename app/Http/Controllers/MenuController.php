<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function menu()
    {
        // Si empleados == 0, redirige a crear el primer empleado
        if (Auth::user()->employees()->count() === 0) {
            return redirect()->route('employee.create.owner');
        }

        // Si ya hay empleados, redirige al menú, sinó a crear el primer empleado
        if (session('employee_role')) {
            return view('menu');
        } else {
            return redirect()->route('employee.login');
        }

    }
}
