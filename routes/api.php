<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\PayoutController;

Route::middleware(['web', 'auth', 'role:Super Admin'])->prefix('admin')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'apiData']);
    Route::get('/instructors/earnings', [PayoutController::class, 'instructorEarnings']);
    Route::post('/payouts', [PayoutController::class, 'store']);
});
