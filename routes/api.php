<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\MaintenanceApiController;
use App\Http\Controllers\Api\V1\BudgetApiController;

Route::prefix('v1')->group(function () {
    
    // GET Summary (Boleh diakses secara terbuka atau tambah auth)
    Route::get('/pts/{id}/maintenance-summary', [MaintenanceApiController::class, 'getSummary']);

    // POST Budget Request (Wajib Token Auth)
    Route::middleware('auth:sanctum')->post('/budget-request', [BudgetApiController::class, 'store']);
});