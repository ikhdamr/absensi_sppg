<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\Ketidakhadiran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Utama (Dashboard) Pegawai
     */
    public function index()
    {
        $user = Auth::user();

        $bulanIni = Carbon::now()->format('m');
        $tahunIni = Carbon::now()->format('Y');

        // Mengambil data presensi dari database
        $presensi = Presensi::where('user_id', $user->id)
                            ->whereMonth('tanggal', $bulanIni)
                            ->whereYear('tanggal', $tahunIni)
                            ->get();

        // ==========================================
        // PERBAIKAN LOGIKA PENGHITUNGAN DI SINI
        // ==========================================
        // 1. Total Hadir (Gabungan antara yang statusnya 'Hadir' maupun 'Terlambat')
        $totalHadir = $presensi->whereIn('status', ['Hadir', 'hadir', 'Terlambat', 'terlambat'])->count();

        // 2. Total Izin/Sakit/Cuti (Diambil dari tabel Ketidakhadiran yang sudah Disetujui)
$totalIzin = Ketidakhadiran::where('user_id', $user->id)
                           ->whereMonth('tanggal_izin', $bulanIni)
                           ->whereYear('tanggal_izin', $tahunIni)
                           ->where('status', 'Disetujui')
                           ->count();

        // 3. Total Alpa
        $totalAlpa = $presensi->whereIn('status', ['Alpa', 'alpa'])->count();

        // 4. Total Terlambat (Ambil dari status yang 'Terlambat' ATAU memiliki menit_terlambat > 0)
        $totalTerlambat = $presensi->filter(function ($item) {
            return in_array(strtolower($item->status), ['terlambat']) || $item->menit_terlambat > 0;
        })->count();
        // ==========================================

        $shiftHariIni = 'Tidak ada shift';
        if ($user->shift) {
            $jamMasuk = Carbon::parse($user->shift->jam_masuk)->format('H:i');
            $jamPulang = Carbon::parse($user->shift->jam_pulang)->format('H:i');
            $shiftHariIni = $user->shift->nama_shift . " ({$jamMasuk} - {$jamPulang})";
        }

        return view('pegawai.dashboard', compact(
            'user',
            'bulanIni',
            'tahunIni',
            'presensi',
            'totalHadir',
            'totalIzin',
            'totalAlpa',
            'totalTerlambat',
            'shiftHariIni'
        ));
    }

    /**
     * Menampilkan Halaman Rekap Presensi Pegawai
     */
    public function rekap()
    {
        $user = Auth::user();
        $bulanIni = Carbon::now()->format('m');
        $tahunIni = Carbon::now()->format('Y');

        $presensi = Presensi::where('user_id', $user->id)
                            ->whereMonth('tanggal', $bulanIni)
                            ->whereYear('tanggal', $tahunIni)
                            ->get();

        return view('pegawai.rekap', compact('user', 'bulanIni', 'tahunIni', 'presensi'));
    }

    /**
     * Menampilkan Form Pengajuan Ketidakhadiran (Cuti/Izin/Sakit)
     */
    public function ketidakhadiran()
    {
        $user = Auth::user();
        $bulanIni = Carbon::now()->format('m');
        $tahunIni = Carbon::now()->format('Y');

        $presensi = Presensi::where('user_id', $user->id)
                            ->whereMonth('tanggal', $bulanIni)
                            ->whereYear('tanggal', $tahunIni)
                            ->get();

        return view('pegawai.ketidakhadiran', compact('user', 'bulanIni', 'tahunIni', 'presensi'));
    }

    /**
     * Proses Simpan Pengajuan Ketidakhadiran
     */
    public function storeKetidakhadiran(Request $request)
    {
        return back()->with('success', 'Pengajuan ketidakhadiran berhasil dikirim.');
    }

    /**
     * Menampilkan Halaman Edit Profil
     */
    public function profil(Request $request)
    {
        $user = $request->user();
        return view('pegawai.profil', compact('user'));
    }

    public function updateProfil(\Illuminate\Http\Request $request)
    {
        // 1. Ambil data pegawai yang sedang login
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        // 2. Validasi input
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'phone'    => 'required|string',
            'alamat'   => 'required|string',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
        ]);

        // 3. Proses Foto Jika Ada yang Diupload Baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama dari folder (jika bukan null dan file fisiknya ada)
            if ($user->photo && file_exists(public_path('uploads/pegawai/' . $user->photo))) {
                @unlink(public_path('uploads/pegawai/' . $user->photo));
            }

            // Buat nama file baru yang unik (agar tidak bentrok dengan file lain dan menghindari cache browser)
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

            // Pindahkan file ke folder public/uploads/pegawai
            $photo->move(public_path('uploads/pegawai'), $photoName);

            // Simpan nama file baru ke database
            $user->photo = $photoName;
        }

        // 4. Update data teks lainnya
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->phone    = $request->phone;
        $user->alamat   = $request->alamat;

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function updatePassword(\Illuminate\Http\Request $request)
    {
        // 1. Validasi Input (Pastikan konfirmasi password cocok)
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // 'confirmed' butuh input 'new_password_confirmation'
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        // 2. Cek apakah password lama yang dimasukkan benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // 3. Ubah password di database
        $user->password = Hash::make($request->new_password);
        $user->save();

        // 4. Log out otomatis dan bersihkan sesi
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 5. Arahkan ke halaman login dengan membawa pesan sukses
        return redirect('/login')->with('success', 'Password Anda berhasil diubah! Silakan login kembali.');
    }
}
