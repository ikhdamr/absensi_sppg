<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ==========================================
    // 1. MEMPROSES DATA LOGIN
    // ==========================================
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil role, hilangkan spasi, dan paksa jadi huruf kecil semua agar aman
            $userRole = strtolower(trim(\Illuminate\Support\Facades\Auth::user()->role));

            // SATPAM PEMILAH JALAN BERDASARKAN ROLE
            if ($userRole === 'admin') {
                // Hapus intended() agar memaksa (force redirect) langsung ke dashboard Admin
                return redirect('/admin/dashboard');
            } else {
                // Force redirect langsung ke dashboard Pegawai
                return redirect('/pegawai/dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau Password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    // ==========================================
    // 2. MEMPROSES LOGOUT
    // ==========================================
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus semua ingatan sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
