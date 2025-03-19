<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeLoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\FirstEmployeeSetupController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('menu');
    }
    return redirect()->route('login');
});

Route::get('/menu', function () {
    return view('menu');
})->middleware(['auth', 'verified'])->name('menu');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Ruta principal al menú, hay un controlador que verifica si hay empleados para redirigir a crear el primer empleado o al menú
    Route::get('/menu', [MenuController::class, 'menu'])->name('menu');

    // Primer acceso, crea primer empleado: el dueño con todos los permisos
    Route::get('/employee/create-owner', [FirstEmployeeSetupController::class, 'showOwnerForm'])->name('employee.create.owner');
    Route::post('/employee/store-owner', [FirstEmployeeSetupController::class, 'storeOwner'])->name('employee.store.owner');

    // Crear otros empleados (si ya existe dueño)
    Route::get('/employee/create', [EmployeeLoginController::class, 'create'])->name('employee.create');
    Route::post('/employee/store', [EmployeeLoginController::class, 'store'])->name('employee.store');

    Route::post('/employee/logout', function () {
        session()->forget(['employee_name', 'employee_role']);
        return redirect()->route('employee.login');
    })->name('employee.logout');

    Route::get('/employee/login', function () {
        return view('employee.login');
    })->name('employee.login');

    Route::post('/employee/login', [EmployeeLoginController::class, 'login'])->name('employee.authenticate');
    Route::get('/employee/login', [EmployeeLoginController::class, 'showLoginForm'])->name('employee.login');
});



require __DIR__.'/auth.php';
