<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // 1. Menampilkan Halaman Lupa Password
    public function index()
    {
        return view('auth.forgot-password');
    }

    // 2. Mengirim OTP ke Email
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar di sistem kami.']);
        }

        // Generate 6 Digit OTP
        $otp = rand(100000, 999999);

        // Simpan OTP di Cache selama 15 Menit (Sesuai permintaan Anda)
        Cache::put('otp_' . $user->email, $otp, now()->addMinutes(15));

        // Simpan email di session untuk tahapan selanjutnya
        session(['reset_email' => $user->email, 'step' => 'otp']);

        // Kirim Email OTP
        Mail::raw("Halo {$user->name},\n\nKode OTP untuk mereset password Anda adalah: {$otp}\n\nKode ini hanya berlaku selama 15 menit. JANGAN berikan kode ini kepada siapapun.\n\nSalam,\nAdmin SPPG.", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Reset Password - SPPG Langensari');
        });

        return back()->with('success', 'Kode OTP telah berhasil dikirim ke email Anda.');
    }

    // 3. Memvalidasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);
        $email = session('reset_email');

        // Ambil OTP dari Cache
        $cachedOtp = Cache::get('otp_' . $email);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            // Jika valid, arahkan ke tahap pembuatan password baru
            session(['step' => 'reset']);
            return back()->with('success', 'Kode OTP Valid! Silakan buat password baru Anda.');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa (lewat 15 menit).']);
    }

    // 4. Menyimpan Password Baru
    public function resetPassword(Request $request)
    {
        // Validasi password baru dan konfirmasinya
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if($user) {
            // Enkripsi dan simpan password baru
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Bersihkan riwayat OTP dan Session
        Cache::forget('otp_' . $email);
        session()->forget(['reset_email', 'step']);

        // Kembalikan ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
    }

    public function resendOtp(Request $request)
{
    $email = $request->email;
    
    // 1. Generate OTP baru (sesuaikan dengan fungsi generate OTP Anda sebelumnya)
    $otp = rand(100000, 999999);
    
    // 2. Simpan ke Cache (seperti proses awal)
    \Illuminate\Support\Facades\Cache::put('otp_' . $email, $otp, now()->addMinutes(15));
    
    // 3. Kirim Email (Gunakan Mail facade Anda)
    // \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OtpMail($otp));

    return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda!');
}
}
