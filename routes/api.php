<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LetterRequestController;
use App\Http\Controllers\Api\LabResultController;
use App\Http\Controllers\Api\DoctorScheduleController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/home', [HomeController::class, 'index']);

    // Letter Requests (pasien)
    Route::prefix('requests')->group(function () {
        Route::get('/', [LetterRequestController::class, 'index']);
        Route::post('/', [LetterRequestController::class, 'store']);
        Route::get('/types', [LetterRequestController::class, 'types']);
        Route::get('/{id}', [LetterRequestController::class, 'show']);
        Route::get('/{id}/download-word', [LetterRequestController::class, 'downloadWord']);
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::get('/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
        Route::patch('/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
        Route::post('/mark-all-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    });

    // Lab Results
    Route::prefix('lab-results')->group(function () {
        Route::get('/', [LabResultController::class, 'index']);
        Route::get('/{id}', [LabResultController::class, 'show']);

        // Petugas Lab only
        Route::middleware('role:petugas_lab')->group(function () {
            Route::post('/', [LabResultController::class, 'store']);
            Route::get('/patients/search', [LabResultController::class, 'patients']);
        });

        // Dokter only
        Route::post('/{id}/validate', [LabResultController::class, 'validateResult'])
            ->middleware('role:dokter');
    });

    // Doctor Schedules (dokter only)
    Route::get('/doctor-schedules', [DoctorScheduleController::class, 'index'])
        ->middleware('role:dokter');
});
