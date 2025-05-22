<?php

use App\Http\Controllers\Travels\TravelRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [RegisterController::class, 'register']);;
Route::post('/login', [LoginController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('/me', [loginController::class, 'getUserInformation']);

    /** Travel request */
    Route::apiResource('travel-requests', TravelRequestController::class);
});
