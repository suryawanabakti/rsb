<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\LetterRequestController;
use App\Http\Controllers\Admin\LetterTypeController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\PetugasLabController;
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Dokter\LabResultController as DokterLabResultController;
use App\Http\Controllers\Dokter\ScheduleController as DokterScheduleController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Pasien\LabResultController as PasienLabResultController;
use App\Http\Controllers\Pasien\LetterRequestController as PasienLetterRequestController;
use App\Http\Controllers\Pasien\NotificationController as PasienNotificationController;
use App\Http\Controllers\PetugasLab\DashboardController as PetugasLabDashboardController;
use App\Http\Controllers\PetugasLab\LabResultController as PetugasLabLabResultController;
use App\Http\Controllers\Pimpinan\DashboardController as PimpinanDashboardController;
use App\Http\Controllers\Pimpinan\ReportController as PimpinanReportController;
use App\Http\Controllers\ProfileController;
use App\Models\LetterRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('welcome');
});

Route::get('/verify/{letterRequest}', [LetterRequestController::class, 'verifyQr'])->name('verify-qr');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [\App\Http\Controllers\Pasien\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Pasien\RegisterController::class, 'register'])->name('register.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        });

        // Shared Letter Requests (Admin & Dokter)
        Route::middleware(['role:admin,dokter'])->group(function () {
            Route::prefix('letter-requests')->name('letter-requests.')->group(function () {
                Route::get('/', [LetterRequestController::class, 'index'])->name('index');
                Route::get('/{letterRequest}', [LetterRequestController::class, 'show'])->name('show');
                Route::patch('/{letterRequest}/status', [LetterRequestController::class, 'updateStatus'])->name('update-status');
                Route::patch('/{letterRequest}/pemeriksaan', [LetterRequestController::class, 'updatePemeriksaan'])->name('update-pemeriksaan');
                Route::get('/{letterRequest}/print-skbn', [LetterRequestController::class, 'printSkbn'])->name('print-skbn');
                Route::get('/{letterRequest}/print-skbj', [LetterRequestController::class, 'printSkbj'])->name('print-skbj');
                Route::get('/{letterRequest}/download-word', [LetterRequestController::class, 'downloadWord'])->name('download-word');
            });
        });

        // Admin Only Management
        Route::middleware(['role:admin'])->group(function () {
            // Letter Types
            Route::resource('letter-types', LetterTypeController::class)->except(['create', 'edit', 'show']);

            // Patients
            Route::prefix('patients')->name('patients.')->group(function () {
                Route::get('/', [PatientController::class, 'index'])->name('index');
                Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
                Route::patch('/{patient}/extra-info', [PatientController::class, 'updateExtraInfo'])->name('update-extra-info');
            });

            // Users Management
            Route::resource('dokters', DokterController::class)->except(['show']);
            Route::resource('petugas-labs', PetugasLabController::class)->except(['show']);
            Route::resource('doctor-schedules', DoctorScheduleController::class)->except(['show']);
        });
    });
});

// Petugas Lab Routes
Route::prefix('petugas-lab')->name('petugas-lab.')->middleware(['auth', 'role:petugas_lab'])->group(function () {
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
Route::prefix('dokter')->name('dokter.')->middleware(['auth', 'role:dokter'])->group(function () {
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

// Pasien Routes
Route::prefix('pasien')->name('pasien.')->middleware(['auth', 'role:pasien'])->group(function () {
    Route::get('/dashboard', [PasienDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('letter-requests')->name('letter-requests.')->group(function () {
        Route::get('/', [PasienLetterRequestController::class, 'index'])->name('index');
        Route::get('/create', [PasienLetterRequestController::class, 'create'])->name('create');
        Route::post('/', [PasienLetterRequestController::class, 'store'])->name('store');
        Route::get('/{letterRequest}', [PasienLetterRequestController::class, 'show'])->name('show');
        Route::get('/{letterRequest}/download-word', [PasienLetterRequestController::class, 'downloadWord'])->name('download-word');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::patch('/{id}/read', [PasienNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [PasienNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });

    Route::prefix('lab-results')->name('lab-results.')->group(function () {
        Route::get('/', [PasienLabResultController::class, 'index'])->name('index');
        Route::get('/{id}', [PasienLabResultController::class, 'show'])->name('show');
    });
});

// Pimpinan Routes
Route::prefix('pimpinan')->name('pimpinan.')->middleware(['auth', 'role:pimpinan'])->group(function () {
    Route::get('/dashboard', [PimpinanDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [PimpinanReportController::class, 'index'])->name('reports.index');
});
