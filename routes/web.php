<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// IMPORT CONTROLLERS
// ==========================================
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PegawaiController as AdminPegawaiController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\HariLiburController;
use App\Http\Controllers\Admin\PresensiController as AdminPresensiController;
use App\Http\Controllers\Admin\KetidakhadiranController as AdminKetidakhadiranController;
use App\Http\Controllers\Admin\ProfileController;

// Pegawai Controllers
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\KetidakhadiranController as PegawaiKetidakhadiranController; 


/*
|--------------------------------------------------------------------------
| 1. HALAMAN UTAMA & AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 2. RUTE MESIN TAP PRESENSI & SCAN QR CODE
|--------------------------------------------------------------------------
*/
Route::get('/presensi/tap', [AdminPresensiController::class, 'tapPage'])->name('presensi.tap');
Route::get('/presensi/api/get-token', [AdminPresensiController::class, 'getNewQrToken']);
Route::get('/presensi/api/check-token/{token}', [AdminPresensiController::class, 'checkQrToken']);


/*
|--------------------------------------------------------------------------
| 3. RUTE KHUSUS ROLE ADMIN (MEMBUTUHKAN LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Kelola Data Pegawai
    Route::get('/admin/pegawai', [AdminPegawaiController::class, 'index']);
    Route::post('/admin/pegawai', [AdminPegawaiController::class, 'store'])->name('pegawai.store');
    Route::put('/admin/pegawai/{id}', [AdminPegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/admin/pegawai/{id}', [AdminPegawaiController::class, 'destroy'])->name('pegawai.destroy');
    Route::put('/admin/pegawai/{id}/reset-password', [AdminPegawaiController::class, 'resetPassword'])->name('admin.pegawai.reset-password');

    // Data Master - Jabatan
    Route::get('/admin/master/jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
    Route::post('/admin/master/jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::put('/admin/master/jabatan/{id}', [JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('/admin/master/jabatan/{id}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');

    // Data Master - Jam Kerja / Shift
    Route::get('/admin/master/jam-kerja', [ShiftController::class, 'index'])->name('jam_kerja.index');
    Route::post('/admin/master/jam-kerja', [ShiftController::class, 'store'])->name('jam_kerja.store');
    Route::put('/admin/master/jam-kerja/{id}', [ShiftController::class, 'update'])->name('jam_kerja.update');
    Route::delete('/admin/master/jam-kerja/{id}', [ShiftController::class, 'destroy'])->name('jam_kerja.destroy');

    // Data Master - Hari Libur
    Route::post('/admin/master/hari-libur/sync', [HariLiburController::class, 'syncLiburNasional'])->name('hari_libur.sync');
    Route::get('/admin/master/hari-libur', [HariLiburController::class, 'index'])->name('hari_libur.index');
    Route::post('/admin/master/hari-libur', [HariLiburController::class, 'store'])->name('hari_libur.store');
    Route::put('/admin/master/hari-libur/{id}', [HariLiburController::class, 'update'])->name('hari_libur.update');
    Route::delete('/admin/master/hari-libur/{id}', [HariLiburController::class, 'destroy'])->name('hari_libur.destroy');

    // Rekap Presensi Admin
    Route::get('/admin/rekap-presensi/harian', [AdminPresensiController::class, 'harian'])->name('presensi.harian');
    Route::get('/admin/rekap-presensi/bulanan', [AdminPresensiController::class, 'bulanan'])->name('presensi.bulanan');
    Route::get('/admin/rekap-presensi/harian/pdf', [AdminPresensiController::class, 'exportPdfHarian'])->name('rekap.harian.pdf');
    Route::get('/admin/rekap-presensi/bulanan/pdf', [AdminPresensiController::class, 'exportPdfBulanan'])->name('rekap.bulanan.pdf');
    
    // Kelola Ketidakhadiran Admin
    Route::get('/admin/ketidakhadiran', [AdminKetidakhadiranController::class, 'index'])->name('admin.ketidakhadiran.index');
    Route::put('/admin/ketidakhadiran/{id}/status', [AdminKetidakhadiranController::class, 'updateStatus'])->name('admin.ketidakhadiran.status');
    
    // Fitur Presensi Manual Admin
    Route::get('/admin/presensi/manual', [AdminPresensiController::class, 'createManual'])->name('admin.presensi.manual');
    Route::post('/admin/presensi/manual', [AdminPresensiController::class, 'storeManual'])->name('admin.presensi.manual.store');

    // Profil & Ubah Password Admin (Diperbaiki & Dirapikan)
    Route::put('/admin/profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/admin/ubah-password', [ProfileController::class, 'updatePassword'])->name('admin.password.update');
});


/*
|--------------------------------------------------------------------------
| 4. RUTE KHUSUS ROLE PEGAWAI (KARYAWAN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Kelompok Rute Halaman Pegawai
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
        Route::get('/rekap', [PegawaiDashboardController::class, 'rekap'])->name('rekap');
        Route::get('/ketidakhadiran', [PegawaiKetidakhadiranController::class, 'index'])->name('ketidakhadiran');
        Route::post('/ketidakhadiran', [PegawaiKetidakhadiranController::class, 'store'])->name('ketidakhadiran.store');
        Route::get('/ketidakhadiran/{id}/edit', [PegawaiKetidakhadiranController::class, 'edit'])->name('ketidakhadiran.edit');
        Route::put('/ketidakhadiran/{id}', [PegawaiKetidakhadiranController::class, 'update'])->name('ketidakhadiran.update');
        Route::delete('/ketidakhadiran/{id}', [PegawaiKetidakhadiranController::class, 'destroy'])->name('ketidakhadiran.destroy');
        Route::get('/profil', [PegawaiDashboardController::class, 'profil'])->name('profil');
        Route::put('/profil', [PegawaiDashboardController::class, 'updateProfil'])->name('profil.update');
        Route::put('/ubah-password', [PegawaiDashboardController::class, 'updatePassword'])->name('ubah-password');
    });

    // Rute untuk menampilkan halaman kamera scanner
    Route::get('/pegawai/scan', [AdminPresensiController::class, 'scanner'])->name('pegawai.scan');

    // Rute untuk memproses hasil scan dari QR Code (Disatukan agar tidak dobel)
    Route::get('/pegawai/scan-qr/{token}', [AdminPresensiController::class, 'scanQrCode'])->name('pegawai.scan.process');

});


/*
|--------------------------------------------------------------------------
| 5. RUTE LUPA PASSWORD (OTP)
|--------------------------------------------------------------------------
*/
Route::get('/lupa-password', [ForgotPasswordController::class, 'index'])->name('lupa-password');
Route::post('/lupa-password/send-otp', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/lupa-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/lupa-password/reset', [ForgotPasswordController::class, 'resetPassword']);
Route::post('/lupa-password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('lupa-password.resend-otp');