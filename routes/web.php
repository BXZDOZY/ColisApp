<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC ROUTES — No authentication required
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/search', [PackageController::class, 'search'])->name('search');
Route::get('/track/{tracking_number}', [PackageController::class, 'publicShow'])->name('tracking.show');

Route::post('/client/search', [PackageController::class, 'clientSearch'])->name('client.search');

Route::get('/support', [SupportTicketController::class, 'create'])->name('support.create');
Route::post('/support', [SupportTicketController::class, 'store'])->name('support.store');

/*
|--------------------------------------------------------------------------
| 🔑 AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 🔐 ADMIN ROUTES — Login required
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Packages Management
    Route::prefix('packages')->name('admin.packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/create', [PackageController::class, 'create'])->name('create');
        Route::post('/', [PackageController::class, 'store'])->name('store');
        Route::get('/{tracking_number}', [PackageController::class, 'show'])->name('show');
        Route::get('/{id}/ticket', [PackageController::class, 'downloadTicket'])->name('ticket');
        Route::post('/{package}/status', [PackageController::class, 'updateStatus'])->name('updateStatus');
        
        // Super Admin only: Edit and Delete packages
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/{package}/edit', [PackageController::class, 'edit'])->name('edit');
            Route::put('/{package}', [PackageController::class, 'update'])->name('update');
            Route::delete('/{package}', [PackageController::class, 'destroy'])->name('destroy');
        });

        // QR Code Scanner and Validation (Employees and Super Admins)
        Route::get('/scan/scanner', [PackageController::class, 'scanner'])->name('scanner');
        Route::get('/validate/{tracking_number}', [PackageController::class, 'validatePackage'])->name('validate');
    });

    // Employee Management (Super Admin only)
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class)->names('admin.employees');
    });

    Route::prefix('support')->name('admin.support.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::get('/{ticket}/pdf', [SupportTicketController::class, 'downloadPDF'])->name('pdf');
    });
});

Route::get('/client/packages', [PackageController::class, 'clientSearch'])->name('client.packages');
Route::get('/client/packages/{id}/ticket', [PackageController::class, 'downloadTicket'])->name('client.packages.ticket');

Route::get('/client/tickets', [SupportTicketController::class, 'clientTickets'])->name('client.tickets');
Route::get('/client/tickets/{ticket}/pdf', [SupportTicketController::class, 'clientDownloadPDF'])->name('client.tickets.pdf');
