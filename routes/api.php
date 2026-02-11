<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LetterRequestController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/home', [HomeController::class, 'index']);

    Route::prefix('requests')->group(function () {
        Route::get('/', [LetterRequestController::class, 'index']);
        Route::post('/', [LetterRequestController::class, 'store']);
        Route::get('/types', [LetterRequestController::class, 'types']);
    });
});
