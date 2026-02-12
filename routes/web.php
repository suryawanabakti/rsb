<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LetterRequestController;
use App\Http\Controllers\Admin\LetterTypeController;
use App\Http\Controllers\Admin\PatientController;

use App\Http\Controllers\PetugasLab\DashboardController as PetugasLabDashboardController;
use App\Http\Controllers\PetugasLab\LabResultController as PetugasLabLabResultController;

use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Dokter\LabResultController as DokterLabResultController;
use App\Http\Controllers\Dokter\ScheduleController as DokterScheduleController;

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

// Petugas Lab Routes
Route::prefix('petugas-lab')->name('petugas-lab.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PetugasLabDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('lab-results')->name('lab-results.')->group(function () {
        Route::get('/', [PetugasLabLabResultController::class, 'index'])->name('index');
        Route::get('/create', [PetugasLabLabResultController::class, 'create'])->name('create');
        Route::post('/', [PetugasLabLabResultController::class, 'store'])->name('store');
        Route::get('/search-patients', [PetugasLabLabResultController::class, 'searchPatients'])->name('search-patients');
        Route::get('/{id}/edit', [PetugasLabLabResultController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PetugasLabLabResultController::class, 'update'])->name('update');
        Route::get('/{id}', [PetugasLabLabResultController::class, 'show'])->name('show');
    });
});

// Dokter Routes
Route::prefix('dokter')->name('dokter.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DokterDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('lab-results')->name('lab-results.')->group(function () {
        Route::get('/', [DokterLabResultController::class, 'index'])->name('index');
        Route::get('/{id}', [DokterLabResultController::class, 'show'])->name('show');
        Route::post('/{id}/validate', [DokterLabResultController::class, 'validate'])->name('validate');
    });

    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/', [DokterScheduleController::class, 'index'])->name('index');
    });
});
