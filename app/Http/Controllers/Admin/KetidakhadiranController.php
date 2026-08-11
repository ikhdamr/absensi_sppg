<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Ketidakhadiran;
use App\Models\Presensi;
use Carbon\Carbon;

class KetidakhadiranController extends Controller
{
    // 1. TAMPILKAN SEMUA DATA PENGAJUAN KARYAWAN
    public function index()
    {
        // Mengambil pengajuan ketidakhadiran, diurutkan dari yang terbaru
        // menggunakan eagar loading 'user' agar bisa mengambil nama karyawan
        $pengajuan = Ketidakhadiran::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.ketidakhadiran.index', compact('pengajuan'));
    }

    /**
     * Memproses Persetujuan (Setuju/Tolak) dari Admin
     */
    public function updateStatus(Request $request, string $id)
    {
        // 1. Cari data pengajuan berdasarkan ID
        $pengajuan = \App\Models\Ketidakhadiran::findOrFail($id);

        // 2. Update status pengajuan (Disetujui / Ditolak)
        $pengajuan->update([
            'status' => $request->status
        ]);

        // 3. JIKA DISETUJUI, OTOMATIS CATAT KE TABEL PRESENSI
        if ($request->status === 'Disetujui') {

            // Gunakan tanggal_izin jika ada, jika tidak gunakan tanggal
            $tanggalAbsen = $pengajuan->tanggal_izin ?? $pengajuan->tanggal;
            $kategoriAbsen = $pengajuan->kategori ?? $pengajuan->keterangan;

            // Cek dulu apakah pegawai sudah punya data presensi di hari tersebut
            // (untuk mencegah data ganda/bentrok)
            $cekPresensi = \App\Models\Presensi::where('user_id', $pengajuan->user_id)
                                               ->where('tanggal', $tanggalAbsen)
                                               ->first();

            if (!$cekPresensi) {
                // Jika belum ada, buatkan data presensinya
                \App\Models\Presensi::create([
                    'user_id'         => $pengajuan->user_id,
                    'tanggal'         => $tanggalAbsen, // INI YANG SEBELUMNYA NULL (ERROR)
                    'jam_masuk'       => null,          // Kosongkan karena tidak absen masuk
                    'jam_keluar'      => null,          // Kosongkan karena tidak absen pulang
                    'status'          => $kategoriAbsen, // Isi dengan: Cuti / Izin / Sakit
                    'menit_terlambat' => 0,             // Pastikan 0, bukan null atau kosong
                ]);
            } else {
                // Jika hari itu dia sudah terlanjur absen masuk, lalu izinnya disetujui,
                // maka cukup timpa/update statusnya menjadi Cuti/Izin/Sakit
                $cekPresensi->update([
                    'status' => $kategoriAbsen
                ]);
            }
        }

        // 4. Redirect kembali dengan pesan sukses
        $pesan = $request->status === 'Disetujui' ? 'Pengajuan berhasil DISETUJUI.' : 'Pengajuan berhasil DITOLAK.';
        return redirect()->back()->with('success', $pesan);
    }
}
