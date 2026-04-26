<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\LorryController;
use App\Http\Controllers\MaintenanceLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AssetMaintenanceController;

Route::post('/maintenance-logs', [MaintenanceLogController::class, 'store'])->name('maintenance.store');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('staff', StaffController::class);
    Route::resource('lorries', LorryController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');
    Route::get('/maintenance/others/{category?}', [AssetMaintenanceController::class, 'index'])->name('assets.index');
    Route::post('/maintenance/store-record', [AssetMaintenanceController::class, 'storeRecord'])->name('assets.store_record');
    Route::post('/assets/register', [AssetMaintenanceController::class, 'registerAsset'])->name('assets.register');
    Route::put('/maintenance/record/{id}', [AssetMaintenanceController::class, 'updateRecord'])->name('assets.update_record');
    Route::delete('/maintenance/record/{id}', [AssetMaintenanceController::class, 'destroyRecord'])->name('assets.destroy_record');
});

require __DIR__.'/auth.php';
