<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CutiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login/dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes - Semua role
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Pegawai - Semua role bisa akses (view berbeda per role)
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');

    // Absensi - Semua role
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/clock-in', [AbsensiController::class, 'clockIn'])->name('absensi.clock-in');
    Route::post('/absensi/clock-out', [AbsensiController::class, 'clockOut'])->name('absensi.clock-out');

    // Cuti - Semua role
    Route::get('/cuti', [CutiController::class, 'index'])->name('cuti.index');
    Route::get('/cuti/create', [CutiController::class, 'create'])->name('cuti.create');
    Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');
    Route::post('/cuti/{id}/approve', [CutiController::class, 'approve'])->name('cuti.approve');
});

// Routes untuk Admin & Kepegawaian
Route::middleware(['auth', 'role:admin,kepegawaian'])->group(function () {
    
    // CRUD Pegawai - Routes dengan path spesifik HARUS didepan parameter {id}
    Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
    
    // Manual Absensi Entry
    Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');

    // Import
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::get('/import/create', [ImportController::class, 'create'])->name('import.create');
    Route::post('/import', [ImportController::class, 'store'])->name('import.store');
    Route::get('/import/template', [ImportController::class, 'template'])->name('import.template');
});

// Routes untuk Admin Only
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Delete Pegawai
    Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    
    // Manajemen User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

// Parameterized pegawai routes - didaftarkan TERAKHIR agar tidak menangkap path spesifik seperti /create
Route::middleware(['auth'])->group(function () {
    Route::get('/pegawai/{id}', [PegawaiController::class, 'show'])->name('pegawai.show');
});
