<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeLoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\FirstEmployeeSetupController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\StablishmentDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CashRegisterController;


// Check if the user is authenticated and redirect to the menu or login page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('menu');
    }
    return redirect()->route('login');
});

Route::get('/menu', function () {
    return view('menu');
})->middleware(['auth', 'verified'])->name('menu');

// Default Breeze routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// App routes
Route::middleware(['auth'])->group(function () {
    // Main route to the menu, there is a controller that checks if there are employees to redirect to create the first employee or to the menu
    Route::get('/menu', [MenuController::class, 'menu'])->name('menu');

    // First access, create the first employee: the owner with all permissions
    Route::get('/employee/create-owner', [FirstEmployeeSetupController::class, 'showOwnerForm'])->name('employee.create.owner');
    Route::post('/employee/store-owner', [FirstEmployeeSetupController::class, 'storeOwner'])->name('employee.store.owner');

    // Create other employees (if the owner already exists)
    Route::get('/employee/create', [EmployeeLoginController::class, 'create'])->name('employee.create');
    Route::post('/employee/store', [EmployeeLoginController::class, 'store'])->name('employee.store');

    // Employee login
    Route::post('/employee/login', [EmployeeLoginController::class, 'login'])->name('employee.authenticate');
    Route::get('/employee/login', [EmployeeLoginController::class, 'showLoginForm'])->name('employee.login');

    // Employees logout
    Route::post('/employee/logout', function () {
        session()->forget(['employee_name', 'employee_role']);
        return redirect()->route('employee.login');
    })->name('employee.logout');

    // Tables manage routes
    Route::get('/tables/manage', [TableController::class, 'manage'])->name('tables.manage');
    Route::post('/tables/add', [TableController::class, 'add'])->name('tables.add');
    Route::patch('/tables/deactivate/{id}', [TableController::class, 'deactivate'])->name('tables.deactivate');
    Route::patch('/tables/activate/{id}', [TableController::class, 'activate'])->name('tables.activate');

    // Tables regular usage
    Route::resource('tables', TableController::class); // Resource manages all the CRUD routes for the TableController
    Route::get('/tables/{number}', [TableController::class, 'show'])->name('tables.show');
    Route::post('/tables/{tableId}/close-order', [OrderController::class, 'closeOrder'])->name('tables.closeOrder');

    // Orders routes
    Route::post('/orders/create/{table}', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/{orderId}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::post('/orders/{orderId}/add-product', [OrderController::class, 'addProduct'])->name('orders.addProduct');
    Route::patch('/orders/{orderId}/products/{productId}', [OrderController::class, 'updateQuantity'])->name('orders.updateQuantity');
    Route::delete('/orders/{orderId}/products/{productId}', [OrderController::class, 'removeProduct'])->name('orders.removeProduct');
    Route::get('/orders/{orderId}/print', [OrderController::class, 'printTicket'])->name('orders.print');

    // Order history
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/by-date', [OrderController::class, 'getOrdersByDate'])->name('orders.byDate');
    Route::get('/orders/{orderId}/view', [OrderController::class, 'viewTicket'])->name('orders.view');

    // Stablishment details routes
    Route::get('/stablishment_details/edit', [StablishmentDetailsController::class, 'edit'])->name('stablishment_details.edit');
    Route::post('/stablishment_details/update', [StablishmentDetailsController::class, 'update'])->name('stablishment_details.update');

    // Accounting routes
    Route::get('/accounting', [AccountingController::class, 'index'])->name('accounting.index');
    Route::get('/accounting/report', [AccountingController::class, 'report'])->name('accounting.report');

    // Products routes CRUD
    Route::resource('products', ProductController::class); // Resource manage all the CRUD routes
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');

    // Cash register routes
    Route::get('/cash-register/open', [CashRegisterController::class, 'openForm'])->name('cash-register.open-form');
    Route::post('/cash-register/open', [CashRegisterController::class, 'open'])->name('cash-register.open');
    Route::get('/cash-register/close', [CashRegisterController::class, 'closeForm'])->name('cash-register.close-form');
    Route::post('/cash-register/close', [CashRegisterController::class, 'close'])->name('cash-register.close');
    Route::get('/cash-register/history', [CashRegisterController::class, 'history'])->name('cash-register.history');

});



require __DIR__.'/auth.php';
