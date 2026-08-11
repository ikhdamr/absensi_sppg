<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; // Wajib ditambahkan untuk memanipulasi waktu

class PresensiController extends Controller
{
    /**
     * 1. Menampilkan Halaman "Mesin" Layar QR Code untuk Admin
     */
    public function tapPage()
    {
        return view('admin.presensi.tap');
    }

    /**
     * 2. API: Mengenerate Token QR Baru (Dipanggil otomatis oleh halaman Admin)
     */
    public function getNewQrToken()
    {
        $token = Str::random(32);
        Cache::put('qr_' . $token, 'pending', now()->addMinutes(5));
        return response()->json(['token' => $token]);
    }

    /**
     * 3. API: Mengecek Status Token (Dijalankan terus-menerus oleh halaman Admin)
     */
    public function checkQrToken(string $token)
    {
        $status = Cache::get('qr_' . $token);

        if ($status && $status !== 'pending') {
            return response()->json([
                'status' => 'scanned',
                'pegawai' => $status
            ]);
        }

        return response()->json(['status' => 'pending']);
    }

   /**
     * 4. Memproses Hasil Scan dari HP Pegawai
     */
    public function scanQrCode(string $token)
    {
        // 1. Cek validitas token
        $status = Cache::get('qr_' . $token);

        if (!$status || $status !== 'pending') {
            return redirect()->route('pegawai.dashboard')->withErrors(['gagal' => 'QR Code tidak valid, sudah digunakan, atau kadaluarsa. Silakan scan ulang!']);
        }

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // 3. Pengecekan Jadwal Shift (CEGAH SCAN DILUAR JADWAL)
        $jamKerja = \App\Models\Shift::find($user->shift_id);

        if (!$jamKerja) {
            return redirect()->route('pegawai.dashboard')->with('warning', 'Anda belum memiliki jadwal shift kerja. Silakan hubungi Admin.');
        }

        // Dapatkan Waktu Saat Ini
        $sekarang = Carbon::now();
        $tanggalHariIni = $sekarang->format('Y-m-d');
        $jamSekarang = $sekarang->format('H:i:s');

        // Pengecekan: Apakah belum masuk rentang waktu absen masuk?
        // Asumsi: Pegawai baru boleh absen paling cepat 1 jam sebelum jam_masuk shift-nya.
        $waktuBukaAbsen = Carbon::parse($tanggalHariIni . ' ' . $jamKerja->jam_masuk)->subHour()->format('H:i:s');

        if ($jamSekarang < $waktuBukaAbsen) {
             return redirect()->route('pegawai.dashboard')->with('warning', 'Belum waktunya absen! Jam kerja Anda (' . $jamKerja->nama_shift . ') dimulai pukul ' . \Carbon\Carbon::parse($jamKerja->jam_masuk)->format('H:i') . ' WIB.');
        }

        // ==========================================
        // 4. CEK APAKAH PEGAWAI SUDAH ABSEN HARI INI
        // ==========================================
        $presensiHariIni = \App\Models\Presensi::where('user_id', $user->id)
                                ->where('tanggal', $tanggalHariIni)
                                ->first();

        $pesanNotif = '';
        $tipeNotif = '';

        if ($presensiHariIni) {
            // JIKA SUDAH ABSEN MASUK -> MAKA INI ADALAH ABSEN PULANG
            if (is_null($presensiHariIni->jam_keluar)) {

                // Cek: Apakah belum waktunya pulang?
                if ($jamSekarang < $jamKerja->jam_keluar) {
                     return redirect()->route('pegawai.dashboard')->with('warning', 'Belum waktunya pulang! Jam pulang Anda adalah pukul ' . \Carbon\Carbon::parse($jamKerja->jam_keluar)->format('H:i') . ' WIB.');
                }

                $presensiHariIni->update([
                    'jam_keluar' => $jamSekarang
                ]);

                $pesanNotif = 'Presensi PULANG berhasil dicatat! Hati-hati di jalan.';
                $tipeNotif = 'success';
            } else {
                // Jika sudah ada jam masuk dan jam keluar
                $pesanNotif = 'Anda sudah menyelesaikan presensi masuk dan pulang untuk hari ini.';
                $tipeNotif = 'warning';
            }
        } else {
            // JIKA BELUM ABSEN -> MAKA INI ADALAH ABSEN MASUK
            $statusPresensi = 'Hadir';
            $pesanNotif = 'Presensi MASUK berhasil dicatat! Anda Tepat Waktu.';
            $tipeNotif = 'success';
            $menitTerlambat = 0; // Default 0 menit

            // Bandingkan Jam Scan vs Batas Toleransi
            if ($jamSekarang > $jamKerja->batas_toleransi) {
                $statusPresensi = 'Terlambat';

                // Hitung total menit keterlambatan
                $jadwalMasuk = Carbon::parse($tanggalHariIni . ' ' . $jamKerja->jam_masuk);
                $menitTerlambat = (int) $jadwalMasuk->diffInMinutes($sekarang);

                // Format menjadi Jam dan Menit
                $jamTelat = floor($menitTerlambat / 60);
                $sisaMenit = $menitTerlambat % 60;

                $teksTerlambat = '';
                if ($jamTelat > 0) {
                    $teksTerlambat .= $jamTelat . ' Jam ';
                }
                if ($sisaMenit > 0 || $jamTelat == 0) {
                    $teksTerlambat .= $sisaMenit . ' Menit';
                }

                $waktuToleransi = Carbon::parse($jamKerja->batas_toleransi)->format('H:i');
                $pesanNotif = "Presensi MASUK dicatat. Anda TERLAMBAT {$teksTerlambat} (Anda Masuk Pada: {$waktuToleransi} WIB).";
                $tipeNotif = 'warning';
            }

            // SIMPAN KE DATABASE
            \App\Models\Presensi::create([
                'user_id'         => $user->id,
                'tanggal'         => $tanggalHariIni,
                'jam_masuk'       => $jamSekarang,
                'jam_keluar'      => null,
                'status'          => $statusPresensi,
                'menit_terlambat' => $menitTerlambat,
            ]);
        }

        // 5. Ubah status token di Cache agar layar Admin berganti
        Cache::put('qr_' . $token, $user->name, now()->addMinutes(1));

        // 6. Kembalikan ke dashboard dengan notifikasi
        return redirect()->route('pegawai.dashboard')->with($tipeNotif, $pesanNotif);
    }

    /**
     * 5. Menampilkan Kamera Scanner untuk Pegawai
     */
    public function scanner()
    {
        return view('pegawai.presensi.scanner');
    }

    /**
     * 6. Menampilkan Halaman Rekap Presensi Harian (Admin)
     */
    public function harian(Request $request)
    {
        // 1. Ambil input tanggal dari filter, jika kosong gunakan tanggal hari ini
        $filterDate = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

        // 2. Buat format tanggal bahasa Indonesia
        $tanggalFormat = Carbon::parse($filterDate)->translatedFormat('d F Y');

        // 3. Ambil data presensi (KITA UBAH NAMANYA MENJADI $rekap)
        $rekap = \App\Models\Presensi::with('user')
                        ->where('tanggal', $filterDate)
                        ->orderBy('jam_masuk', 'asc')
                        ->get();

        // 4. Kirim $rekap ke tampilan blade
        return view('admin.rekap.harian', compact('rekap', 'filterDate', 'tanggalFormat'));
    }

    /**
     * 7. Export PDF Rekap Harian
     */
    public function exportPdfHarian(Request $request)
    {
        // 1. Ambil input tanggal dari URL (jika ada), default hari ini
        $filterDate = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

        // 2. Format tanggal untuk ditampilkan di Kop Surat PDF
        $tanggalFormat = Carbon::parse($filterDate)->translatedFormat('d F Y');

        // 3. Ambil data presensi dari database
        // (KITA GUNAKAN VARIABEL $presensi AGAR COCOK DENGAN BLADE PDF ANDA)
        $presensi = \App\Models\Presensi::with('user')
                        ->where('tanggal', $filterDate)
                        ->orderBy('jam_masuk', 'asc')
                        ->get();

        // 4. Generate PDF
        // Pastikan nama file view-nya adalah pdf_harian sesuai milik Anda
        $pdf = Pdf::loadView('admin.rekap.pdf_harian', compact('presensi', 'tanggalFormat', 'filterDate'));

        // 5. Download otomatis file PDF-nya
        return $pdf->download('Laporan-Presensi-' . $filterDate . '.pdf');
    }

    /**
     * 8. Menampilkan Halaman Rekap Presensi Bulanan (Admin)
     */
    public function bulanan(Request $request)
    {
        // 1. Ambil input bulan dan tahun dari filter
        $filterMonth = $request->input('bulan', \Carbon\Carbon::now()->format('m'));
        $filterYear = $request->input('tahun', \Carbon\Carbon::now()->format('Y'));

        // 2. Buat duplikat variabel agar file Blade aman dari error (Bahasa Inggris & Indonesia)
        $month = $filterMonth;
        $tahun = $filterYear;
        $year = $filterYear; // <--- INI TAMBAHANNYA

        // 3. Daftar bulan untuk dropdown filter di tampilan
        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Ambil nama bulan (misal: 'Agustus')
        $namaBulanTerpilih = $daftarBulan[(int)$filterMonth];
        $bulanFormat = $namaBulanTerpilih . ' ' . $filterYear;

        // 4. Ambil data seluruh pegawai beserta hitungan rekapnya di bulan tersebut
        $rekap = \App\Models\User::where('role', 'pegawai')->get()->map(function ($user) use ($filterMonth, $filterYear) {

            // Cari data presensi milik user ini pada bulan dan tahun yang dipilih
            $presensi = \App\Models\Presensi::where('user_id', $user->id)
                            ->whereMonth('tanggal', $filterMonth)
                            ->whereYear('tanggal', $filterYear)
                            ->get();

            // Hitung statistik per pegawai
            $totalHadir = $presensi->whereIn('status', ['Hadir', 'hadir', 'Terlambat', 'terlambat'])->count();

            $totalTerlambat = $presensi->filter(function ($item) {
                return in_array(strtolower($item->status), ['terlambat']) || $item->menit_terlambat > 0;
            })->count();

            $totalIzin = $presensi->whereIn('status', ['Izin', 'izin', 'Sakit', 'sakit', 'Cuti', 'cuti'])->count();
            $totalAlpa = $presensi->whereIn('status', ['Alpa', 'alpa'])->count();

            // Sisipkan hasil hitungan ke dalam objek user
            $user->total_hadir = $totalHadir;
            $user->total_terlambat = $totalTerlambat;
            $user->total_izin = $totalIzin;
            $user->total_alpa = $totalAlpa;

            return $user;
        });

        // 5. Kirim semua variabel ke tampilan rekap bulanan (Tambahkan 'year' di sini)
        return view('admin.rekap.bulanan', compact(
            'rekap',
            'filterMonth',
            'filterYear',
            'month',
            'tahun',
            'year',
            'daftarBulan',
            'namaBulanTerpilih',
            'bulanFormat'
        ));
    }

    /**
     * 9. Export PDF Rekap Bulanan
     */
    public function exportPdfBulanan(Request $request)
    {
        // 1. Ambil input bulan dan tahun
        $filterMonth = $request->input('bulan', \Carbon\Carbon::now()->format('m'));
        $filterYear = $request->input('tahun', \Carbon\Carbon::now()->format('Y'));

        // 2. Format nama bulan untuk Kop Surat PDF
        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulanTerpilih = $daftarBulan[(int)$filterMonth];
        $bulanFormat = $namaBulanTerpilih . ' ' . $filterYear;

        // 3. Ambil data rekap (sama persis dengan fungsi bulanan)
        $rekap = \App\Models\User::where('role', 'pegawai')->get()->map(function ($user) use ($filterMonth, $filterYear) {

            $presensi = \App\Models\Presensi::where('user_id', $user->id)
                            ->whereMonth('tanggal', $filterMonth)
                            ->whereYear('tanggal', $filterYear)
                            ->get();

            $user->total_hadir = $presensi->whereIn('status', ['Hadir', 'hadir', 'Terlambat', 'terlambat'])->count();

            $user->total_terlambat = $presensi->filter(function ($item) {
                return in_array(strtolower($item->status), ['terlambat']) || $item->menit_terlambat > 0;
            })->count();

            $user->total_izin = $presensi->whereIn('status', ['Izin', 'izin', 'Sakit', 'sakit', 'Cuti', 'cuti'])->count();
            $user->total_alpa = $presensi->whereIn('status', ['Alpa', 'alpa'])->count();

            return $user;
        });

        // 4. Generate PDF (Memanggil file view pdf_bulanan)
        $pdf = Pdf::loadView('admin.rekap.pdf_bulanan', compact('rekap', 'bulanFormat'));

        // 5. Download otomatis
        return $pdf->download('Rekap-Bulanan-' . $namaBulanTerpilih . '-' . $filterYear . '.pdf');
    }

    // ====================================
    // 10. MENAMPILKAN FORM ABSEN MANUAL
    // ====================================
    public function createManual()
    {
        // Ambil semua data pegawai untuk dimasukkan ke dropdown
        $pegawai = \App\Models\User::where('role', 'pegawai')->orderBy('name', 'asc')->get();
        return view('admin.presensi.manual', compact('pegawai'));
    }

    // ====================================
    // 11. MENYIMPAN DATA ABSEN MANUAL (MASSAL)
    // ====================================
    public function storeManual(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'presensi' => 'required|array', // Memastikan ada data yang dicentang
        ], [
            'presensi.required' => 'Anda belum mencentang status presensi satupun pegawai.'
        ]);

        $tanggal = $request->tanggal;
        $jumlahDisimpan = 0;

        // Looping semua pegawai yang dicentang di tabel
        foreach ($request->presensi as $user_id => $status) {

            // Cek apakah pegawai sudah absen di tanggal tersebut
            $presensi = \App\Models\Presensi::where('user_id', $user_id)
                                            ->where('tanggal', $tanggal)
                                            ->first();

            // Set default jam jika statusnya Hadir (Bisa disesuaikan nanti)
            $jamMasuk = ($status == 'Hadir') ? '08:00:00' : null;
            $jamKeluar = ($status == 'Hadir') ? '16:00:00' : null;

            if ($presensi) {
                // Jika sudah ada data, UPDATE statusnya
                $presensi->update([
                    'status' => $status,
                ]);
            } else {
                // Jika belum ada data, BUAT BARU
                \App\Models\Presensi::create([
                    'user_id'         => $user_id,
                    'tanggal'         => $tanggal,
                    'jam_masuk'       => $jamMasuk,
                    'jam_keluar'      => $jamKeluar,
                    'status'          => $status,
                    'menit_terlambat' => 0
                ]);
            }

            $jumlahDisimpan++;
        }

        return back()->with('success', 'Data presensi untuk ' . $jumlahDisimpan . ' pegawai di tanggal ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') . ' berhasil disimpan!');
    }
}
