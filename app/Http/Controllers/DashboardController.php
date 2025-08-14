<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    // Halaman dashboard yang menampilkan seluruh pengguna
    public function index()
    {
        // Mengambil seluruh data user
        $users = User::all();

        // Menampilkan dashboard dengan data pengguna
        return view('dashboard', compact('users'));
    }
}

