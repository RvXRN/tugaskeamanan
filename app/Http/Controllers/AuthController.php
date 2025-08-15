<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\RateLimit;
use Carbon\Carbon;
class AuthController extends Controller
{
    public function showLoginForm(Request $request)
{

        return view('auth.login');
    }

    // Menangani proses login
   public function login(Request $request)
{
    // Validasi input pengguna
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Ambil jumlah percobaan login dari session atau set default 0
    $attempts = session('login_attempts', 0);

    // Jika terlalu banyak percobaan
    if ($attempts >= 5) {
        // Menyimpan waktu batas percobaan (10 menit ke depan)
        $timeout = now()->addMinutes(10);
        session(['login_timeout' => $timeout]);

        return back()->withErrors([
            'email' => 'Too many login attempts. Please try again in 10 minutes.',
        ]);
    }

    // Proses autentikasi
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        // Reset percobaan login setelah berhasil
        session()->forget('login_attempts');

        // Regenerasi session setelah login sukses
        $request->session()->regenerate();

        // Redirect ke dashboard setelah login sukses
        return redirect()->intended('/dashboard');
    }

    // Jika gagal login, tambahkan percobaan ke session
    session(['login_attempts' => $attempts + 1]);

    // Kembalikan error jika login gagal
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}


    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Menangani proses registrasi
    public function register(Request $request)
    {
        // Validasi data input
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Membuat pengguna baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke dashboard setelah berhasil registrasi
        return redirect('/dashboard');
    }

    // Menangani proses logout
    public function logout(Request $request)
    {
        Auth::logout(); // Menghapus sesi login
        $request->session()->invalidate(); // Menghapus data sesi
        $request->session()->regenerateToken(); // Menyegarkan token CSRF
        session()->forget('login_attempts');
        return redirect('/login '); // Mengarahkan pengguna ke halaman depan setelah logout
    }
}
