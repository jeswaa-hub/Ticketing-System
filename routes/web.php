<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;

// Homepage
Route::get('/', function () {
    return redirect()->route('login');
});

// Login & Logout
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/tickets', [AdminController::class, 'tickets'])->name('admin.tickets');
    Route::get('/admin/tickets/export', [AdminController::class, 'exportTickets'])->name('admin.tickets.export.list');
    Route::post('/admin/tickets', [AdminController::class, 'storeTicket'])->name('admin.tickets.store');
    Route::get('/admin/tickets/{ticket}', [AdminController::class, 'showTicket'])->name('admin.tickets.show');
    Route::get('/admin/tickets/{ticket}/export', [AdminController::class, 'exportTicket'])->name('admin.tickets.export');
    Route::patch('/admin/tickets/{ticket}/status', [AdminController::class, 'updateTicketStatus'])->name('admin.tickets.status.update');
    Route::patch('/admin/tickets/{ticket}/time-end', [AdminController::class, 'updateTicketTimeEnd'])->name('admin.tickets.time-end.update');
    Route::post('/admin/tickets/{ticket}/comments', [AdminController::class, 'storeTicketComment'])->name('admin.tickets.comments.store');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::patch('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/admin/users/{user}/activity', [AdminController::class, 'userActivity'])->name('admin.users.activity');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::patch('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/admin/settings/categories', [AdminController::class, 'storeCategory'])->name('admin.settings.categories.store');
    Route::delete('/admin/settings/categories/{categoryId}', [AdminController::class, 'destroyCategory'])->name('admin.settings.categories.destroy');
});

// Employee Routes
Route::middleware(['auth', 'employee'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::post('/employee/tickets', [EmployeeController::class, 'storeTicket'])->name('employee.tickets.store');
    Route::get('/employee/tickets', [EmployeeController::class, 'tickets'])->name('employee.tickets');
});
