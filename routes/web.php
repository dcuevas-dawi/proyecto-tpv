<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeLoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\FirstEmployeeSetupController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;

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

    // Employees login
    Route::get('/employee/login', function () {
        return view('employee.login');
    })->name('employee.login');

    Route::post('/employee/logout', function () {
        session()->forget(['employee_name', 'employee_role']);
        return redirect()->route('employee.login');
    })->name('employee.logout');

    Route::post('/employee/login', [EmployeeLoginController::class, 'login'])->name('employee.authenticate');
    Route::get('/employee/login', [EmployeeLoginController::class, 'showLoginForm'])->name('employee.login');

    // Tables manage routes
    Route::get('/tables/manage', [TableController::class, 'manage'])->name('tables.manage');
    Route::post('/tables/add', [TableController::class, 'add'])->name('tables.add');
    Route::patch('/tables/deactivate/{id}', [TableController::class, 'deactivate'])->name('tables.deactivate');
    Route::patch('/tables/activate/{id}', [TableController::class, 'activate'])->name('tables.activate');

    // Tables regular usage
    Route::resource('tables', TableController::class); // Resource manage all the CRUD routes for the TableController
    Route::get('/tables/{number}', [TableController::class, 'show'])->name('tables.show');
    Route::post('/tables/{tableId}/close-order', [OrderController::class, 'closeOrder'])->name('tables.closeOrder');

    // Orders routes
    Route::post('/orders/create/{table}', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/{orderId}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::post('/orders/{orderId}/add-product', [OrderController::class, 'addProduct'])->name('orders.addProduct');
    Route::patch('/orders/{orderId}/products/{productId}', [OrderController::class, 'updateQuantity'])->name('orders.updateQuantity');
    Route::delete('/orders/{orderId}/products/{productId}', [OrderController::class, 'removeProduct'])->name('orders.removeProduct');
    Route::get('/orders/{orderId}/print', [OrderController::class, 'printTicket'])->name('orders.print');
    // History
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/by-date', [OrderController::class, 'getOrdersByDate'])->name('orders.byDate');

    // Stablishment details routes
    Route::get('/stablishment_details/edit', [App\Http\Controllers\StablishmentDetailsController::class, 'edit'])->name('stablishment_details.edit');
    Route::post('/stablishment_details/update', [App\Http\Controllers\StablishmentDetailsController::class, 'update'])->name('stablishment_details.update');
});



require __DIR__.'/auth.php';
