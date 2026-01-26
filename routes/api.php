<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
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
    });

    Route::prefix('vehicles')->group(function () {
        Route::post('/', [VehicleController::class, 'store']);
    });
});
