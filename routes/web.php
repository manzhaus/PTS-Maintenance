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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BudgetRequestController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Route (Shared)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. Lorry Maintenance (Supervisor)
    // Menukar nama resource supaya sepadan dengan Navbar (lorry.index)
    Route::resource('lorry', LorryController::class)->names([
        'index' => 'lorry.index',
        'create' => 'lorry.create',
        'store' => 'lorry.store',
        'show' => 'lorry.show',
        'edit' => 'lorry.edit',
        'update' => 'lorry.update',
        'destroy' => 'lorry.destroy',
    ]);
    Route::post('/maintenance-logs', [MaintenanceLogController::class, 'store'])->name('maintenance.store');

    // 2. Asset/Others Maintenance (Supervisor)
    Route::get('/maintenance/others/{category?}', [AssetMaintenanceController::class, 'index'])->name('assets.index');
    Route::post('/maintenance/store-record', [AssetMaintenanceController::class, 'storeRecord'])->name('assets.store_record');
    Route::post('/assets/register', [AssetMaintenanceController::class, 'registerAsset'])->name('assets.register');
    Route::put('/maintenance/record/{id}', [AssetMaintenanceController::class, 'updateRecord'])->name('assets.update_record');
    Route::delete('/maintenance/record/{id}', [AssetMaintenanceController::class, 'destroyRecord'])->name('assets.destroy_record');

    // 3. Reports (Admin & Supervisor)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    // 4. Budget Management (Admin Only)
    Route::prefix('admin')->group(function () {
        Route::get('/budgets', [BudgetRequestController::class, 'index'])->name('admin.budgets.index');
        Route::post('/budgets/approve/{id}', [BudgetRequestController::class, 'approve'])->name('admin.budgets.approve');
        Route::post('/budgets/reject/{id}', [BudgetRequestController::class, 'reject'])->name('admin.budgets.reject');
        Route::patch('/budgets/update-base/{user}', [BudgetRequestController::class, 'updateBase'])->name('admin.budgets.updateBase');
    });

    // 5. Budget Requests (Supervisor Only)
    Route::get('/my-budget-requests', [BudgetRequestController::class, 'myRequests'])->name('budget_requests.index'); // Selaras dengan Navbar
    Route::post('/budget-request/store', [BudgetRequestController::class, 'store'])->name('budget.request.store');

    // Staff Management
    Route::resource('staff', StaffController::class);
});

require __DIR__.'/auth.php';