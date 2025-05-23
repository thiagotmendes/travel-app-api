<?php

use App\Http\Controllers\Travels\TravelRequestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Informações do usuário autenticado
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [LoginController::class, 'getUserInformation']);

    Route::middleware('role:user|admin')->group(function () {

        Route::apiResource('travel-requests', TravelRequestController::class)->except(['update']);

        Route::match(['put', 'patch'], 'travel-requests/{travel_request}', [TravelRequestController::class, 'update']);
    });


    Route::middleware('role:admin')->group(function () {
         Route::patch('travel-requests/{id}/status', [TravelRequestController::class, 'updateStatus']);
    });
});
