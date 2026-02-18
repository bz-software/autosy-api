<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VehicleController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('customers')->group(function () {
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/', [CustomerController::class, 'search']);
        Route::get('/{customer}/vehicles', [VehicleController::class, 'byCustomer']);
        Route::put('/{id}', [CustomerController::class, 'update']);
        Route::get('/{id}/appointments', [AppointmentController::class, 'byCustomer']);
    });

    Route::prefix('vehicles')->group(function () {
        Route::post('/', [VehicleController::class, 'store']);
        Route::put('/{id}', [VehicleController::class, 'update']);
    });

    Route::prefix('appointments')->group(function () {
        Route::post('/', [AppointmentController::class, 'store']);
        Route::get('/', [AppointmentController::class, 'index']);

        Route::patch('/{id}/start-diagnosis', [AppointmentController::class, 'startDiagnosis']);
        Route::patch('/{id}/request-approval', [AppointmentController::class, 'requestApproval']);   
        Route::patch('/{id}/approve', [AppointmentController::class, 'approveDiagnosis']);
        Route::patch('/{id}/finalize', [AppointmentController::class, 'finalize']);

        Route::post('/{appointment}/services', [AppointmentServiceController::class, 'store']);
        Route::delete('/{appointment}/services/{service}', [AppointmentServiceController::class, 'destroy']);
    });

    Route::prefix('services')->group(function() {
        Route::get('/', [ServiceController::class, 'index']);
        Route::post('/', [ServiceController::class, 'store']);
        Route::put('/{id}', [ServiceController::class, 'update']);
        Route::delete('/{id}', [ServiceController::class, 'destroy']);
    });
});

Route::prefix('appointments')->group(function () {
    Route::get('/{id}/approval', [AppointmentController::class, 'approval']);
    Route::get('/{id}/approval/pdf', [AppointmentController::class, 'pdfDiagnosis']);
});
