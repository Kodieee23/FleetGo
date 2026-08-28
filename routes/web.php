<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) return redirect('/admin/dashboard');
        if ($user->isManager()) return redirect('/manager/dashboard');
        if ($user->isDriver()) return redirect('/driver/dashboard');
    }
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;

// Protected Routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::get('/trips', [TripController::class, 'adminIndex'])->name('trips');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::resource('vehicles', VehicleController::class)->except(['create', 'show', 'edit']);
});

Route::middleware(['auth', 'is_manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'manager'])->name('dashboard');
    Route::get('/trips', [TripController::class, 'managerIndex'])->name('trips');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
});

Route::middleware(['auth', 'is_driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'driver'])->name('dashboard');
    Route::get('/trips', [TripController::class, 'index'])->name('trips');
    Route::get('/trips/create', [TripController::class, 'create'])->name('trips.create');
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
    Route::post('/trips/{trip}/end', [TripController::class, 'end'])->name('trips.end');
});
