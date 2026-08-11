<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; 

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

            // ==========================================================
            // PENAMBAHAN BARU: CEGAH ABSEN MASUK JIKA SHIFT SUDAH LEWAT
            // ==========================================================
            if ($jamSekarang > $jamKerja->jam_keluar) {
                return redirect()->route('pegawai.dashboard')->with('error', 'Gagal Absen! Waktu shift Anda sudah berakhir pada pukul ' . \Carbon\Carbon::parse($jamKerja->jam_keluar)->format('H:i') . ' WIB.');
            }

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
        $filterDate = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $tanggalFormat = Carbon::parse($filterDate)->translatedFormat('d F Y');
        
        $rekap = \App\Models\Presensi::with('user')
                        ->where('tanggal', $filterDate)
                        ->orderBy('jam_masuk', 'asc')
                        ->get();

        return view('admin.rekap.harian', compact('rekap', 'filterDate', 'tanggalFormat'));
    }

    /**
     * 7. Export PDF Rekap Harian
     */
    public function exportPdfHarian(Request $request)
    {
        $filterDate = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $tanggalFormat = Carbon::parse($filterDate)->translatedFormat('d F Y');

        $presensi = \App\Models\Presensi::with('user')
                        ->where('tanggal', $filterDate)
                        ->orderBy('jam_masuk', 'asc')
                        ->get();

        $pdf = Pdf::loadView('admin.rekap.pdf_harian', compact('presensi', 'tanggalFormat', 'filterDate'));
        return $pdf->download('Laporan-Presensi-' . $filterDate . '.pdf');
    }

    /**
     * 8. Menampilkan Halaman Rekap Presensi Bulanan (Admin)
     */
    public function bulanan(Request $request)
    {
        $filterMonth = $request->input('bulan', \Carbon\Carbon::now()->format('m'));
        $filterYear = $request->input('tahun', \Carbon\Carbon::now()->format('Y'));

        $month = $filterMonth;
        $tahun = $filterYear;
        $year = $filterYear; 

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $namaBulanTerpilih = $daftarBulan[(int)$filterMonth];
        $bulanFormat = $namaBulanTerpilih . ' ' . $filterYear;

        $rekap = \App\Models\User::where('role', 'pegawai')->get()->map(function ($user) use ($filterMonth, $filterYear) {

            $presensi = \App\Models\Presensi::where('user_id', $user->id)
                            ->whereMonth('tanggal', $filterMonth)
                            ->whereYear('tanggal', $filterYear)
                            ->get();

            $totalHadir = $presensi->whereIn('status', ['Hadir', 'hadir', 'Terlambat', 'terlambat'])->count();

            $totalTerlambat = $presensi->filter(function ($item) {
                return in_array(strtolower($item->status), ['terlambat']) || $item->menit_terlambat > 0;
            })->count();

            $totalIzin = $presensi->whereIn('status', ['Izin', 'izin', 'Sakit', 'sakit', 'Cuti', 'cuti'])->count();
            $totalAlpa = $presensi->whereIn('status', ['Alpa', 'alpa'])->count();

            $user->total_hadir = $totalHadir;
            $user->total_terlambat = $totalTerlambat;
            $user->total_izin = $totalIzin;
            $user->total_alpa = $totalAlpa;

            return $user;
        });

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
        $filterMonth = $request->input('bulan', \Carbon\Carbon::now()->format('m'));
        $filterYear = $request->input('tahun', \Carbon\Carbon::now()->format('Y'));

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulanTerpilih = $daftarBulan[(int)$filterMonth];
        $bulanFormat = $namaBulanTerpilih . ' ' . $filterYear;

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

        $pdf = Pdf::loadView('admin.rekap.pdf_bulanan', compact('rekap', 'bulanFormat'));
        return $pdf->download('Rekap-Bulanan-' . $namaBulanTerpilih . '-' . $filterYear . '.pdf');
    }

    // ====================================
    // 10. MENAMPILKAN FORM ABSEN MANUAL
    // ====================================
    public function createManual()
    {
        $pegawai = \App\Models\User::where('role', '!=', 'admin')
                           ->orderBy('name', 'asc')
                           ->get();
                           
        return view('admin.presensi.manual', compact('pegawai'));
    }

    // ====================================
    // 11. MENYIMPAN DATA ABSEN MANUAL
    // ====================================
    public function storeManual(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'presensi' => 'required|array', 
        ], [
            'presensi.required' => 'Anda belum mencentang status presensi satupun pegawai.'
        ]);

        $tanggal = $request->tanggal;
        $jumlahDisimpan = 0;

        foreach ($request->presensi as $user_id => $status) {

            $presensi = \App\Models\Presensi::where('user_id', $user_id)
                                            ->where('tanggal', $tanggal)
                                            ->first();

            if ($status == 'Hadir') {
                // Ambil input jam dari form (Jika kosong, jadikan null)
                $jamMasukInput = $request->jam_masuk[$user_id] ?? null;
                $jamKeluarInput = $request->jam_keluar[$user_id] ?? null;

                if ($presensi) {
                    // Update data yang sudah ada (jangan timpa dengan null jika form dikosongkan)
                    $presensi->update([
                        'status'     => 'Hadir',
                        'jam_masuk'  => $jamMasukInput ?: $presensi->jam_masuk,
                        'jam_keluar' => $jamKeluarInput ?: $presensi->jam_keluar,
                    ]);
                } else {
                    // Buat data baru
                    \App\Models\Presensi::create([
                        'user_id'         => $user_id,
                        'tanggal'         => $tanggal,
                        'jam_masuk'       => $jamMasukInput,
                        'jam_keluar'      => $jamKeluarInput,
                        'status'          => 'Hadir',
                        'menit_terlambat' => 0 
                    ]);
                }
            } else {
                // Jika Izin / Sakit / Alpa (Hapus jam masuk dan keluarnya)
                if ($presensi) {
                    $presensi->update([
                        'status'     => $status,
                        'jam_masuk'  => null,
                        'jam_keluar' => null
                    ]);
                } else {
                    \App\Models\Presensi::create([
                        'user_id'         => $user_id,
                        'tanggal'         => $tanggal,
                        'jam_masuk'       => null,
                        'jam_keluar'      => null,
                        'status'          => $status,
                        'menit_terlambat' => 0
                    ]);
                }
            }
            $jumlahDisimpan++;
        }

        return back()->with('success', 'Data presensi berhasil disimpan!');
    }
}