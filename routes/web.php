<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Menampilkan form login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// Proses login
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,10');

// Menampilkan form registrasi
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');

// Proses registrasi
Route::post('register', [AuthController::class, 'register']);

// Logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Halaman dashboard, hanya dapat diakses setelah login
Route::middleware('auth')->get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/clear-session', function () {
    session()->flush();  // Menghapus semua session
    return 'Session cleared!';
});