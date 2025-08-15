<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
Route::get('/', function(){
    return redirect()->route('login');
});
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
Route::get('/files', function () {
    // Tentukan path folder yang ingin di-list
    $directoryPath = storage_path('app/public/files');
    
    // Cek apakah folder ada
    if (File::exists($directoryPath)) {
        // Ambil daftar file dalam folder
        $files = File::files($directoryPath);
        
        // Tampilkan daftar file
        return view('file-list', compact('files'));
    } else {
        return "Directory does not exist.";
    }
});