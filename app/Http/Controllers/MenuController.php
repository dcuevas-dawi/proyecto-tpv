<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Logic for the menu, aoviding include this in the web.php
class MenuController extends Controller
{
    public function menu()
    {
        // If employees == 0, redirect to create the first employee, the owner
        if (Auth::user()->employees()->count() === 0) {
            return redirect()->route('employee.create.owner');
        }

        // If there are employees, redirect to the menu, if not to the employee login
        if (session('employee_role')) {
            return view('menu');
        } else {
            return redirect()->route('employee.login');
        }

    }
}
