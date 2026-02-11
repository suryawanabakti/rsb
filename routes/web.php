<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LetterRequestController;
use App\Http\Controllers\Admin\LetterTypeController;
use App\Http\Controllers\Admin\PatientController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Letter Requests
        Route::prefix('letter-requests')->name('letter-requests.')->group(function () {
            Route::get('/', [LetterRequestController::class, 'index'])->name('index');
            Route::get('/{letterRequest}', [LetterRequestController::class, 'show'])->name('show');
            Route::patch('/{letterRequest}/status', [LetterRequestController::class, 'updateStatus'])->name('update-status');
        });

        // Letter Types
        Route::resource('letter-types', LetterTypeController::class)->except(['create', 'edit', 'show']);

        // Patients
        Route::prefix('patients')->name('patients.')->group(function () {
            Route::get('/', [PatientController::class, 'index'])->name('index');
            Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
        });
    });
});
