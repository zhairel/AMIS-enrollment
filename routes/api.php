<?php

use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider or bootstrap/app.php
| within a group which is assigned the "api" middleware group.
|
*/

Route::prefix('v1')->group(function () {
    Route::post('/enrollment/2026-2027', [EnrollmentController::class, 'store2026'])->name('api.enrollment.2026');
});
