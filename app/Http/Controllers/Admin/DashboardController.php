<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Presensi;
use App\Models\HariLibur;
use App\Models\Shift;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = Carbon::now()->year;
        $selectedMonth = (int) $request->input('bulan', Carbon::now()->month);
        $now = Carbon::now();
        $todayStr = $now->format('Y-m-d'); // Tanggal hari ini untuk perbandingan

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $namaBulanTerpilih = $daftarBulan[$selectedMonth];

        // ==========================================
        // 1. LOGIKA GRAFIK BULANAN
        // ==========================================
        $totalPegawai = \App\Models\User::where('role', 'pegawai')->count();

        $hariLiburBulanIni = [];
        if (class_exists('\App\Models\HariLibur')) {
            $hariLiburBulanIni = \App\Models\HariLibur::whereMonth('tanggal', $selectedMonth)
                                          ->whereYear('tanggal', $currentYear)
                                          ->pluck('tanggal')
                                          ->toArray();
        }

        $liburFormatted = array_map(function($tgl) {
            return Carbon::parse($tgl)->format('Y-m-d');
        }, $hariLiburBulanIni);

        $presensiBulanIni = \App\Models\Presensi::whereMonth('tanggal', $selectedMonth)
                                    ->whereYear('tanggal', $currentYear)
                                    ->get();

        $chartLabels = [];
        $chartHadir = [];
        $chartIzin = [];
        $chartAlpa = [];

        $totalHadirSebulan = 0;
        $totalIzinSebulan = 0;
        $totalAlpaSebulan = 0;

        $daysInMonth = Carbon::create($currentYear, $selectedMonth, 1)->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateCarbon = Carbon::createFromDate($currentYear, $selectedMonth, $i);
            $dateStr = $dateCarbon->format('Y-m-d');

            $chartLabels[] = $i;
            $isMinggu = $dateCarbon->isSunday();
            $isLibur = in_array($dateStr, $liburFormatted);

            // Jika hari Minggu atau Libur Nasional, kosongkan semua
            if ($isMinggu || $isLibur) {
                $chartHadir[] = 0;
                $chartIzin[]  = 0;
                $chartAlpa[]  = 0;
            } else {
                $dailyData = $presensiBulanIni->where('tanggal', $dateStr);

                $hadir = $dailyData->whereIn('status', ['Hadir', 'hadir', 'Terlambat', 'terlambat'])->count();
                $izin = $dailyData->whereIn('status', ['Izin', 'izin', 'Sakit', 'sakit', 'Cuti', 'cuti'])->count();
                
                $alpa = 0; // Default alpa adalah 0

                // LOGIKA BARU: Cek apakah hari sudah lewat
                if ($dateStr < $todayStr) {
                    // Jika hari kemarin dan seterusnya (masa lalu), sisa pegawai yang tidak hadir/izin dianggap Alpa
                    $alpa = $totalPegawai - ($hadir + $izin);
                } elseif ($dateStr == $todayStr) {
                    // Jika hari ini, tunggu sampai shift selesai (sementara set 0 agar grafik rapi saat jam kerja berjalan)
                    // Jika ingin ketat, Anda bisa memanggil logika shift aktif di sini
                    $alpa = 0; 
                } else {
                    // Jika besok dan masa depan, alpa = 0 (belum terjadi)
                    $alpa = 0;
                }

                if ($alpa < 0) $alpa = 0;

                $chartHadir[] = $hadir;
                $chartIzin[]  = $izin;
                $chartAlpa[]  = $alpa;

                $totalHadirSebulan += $hadir;
                $totalIzinSebulan += $izin;
                $totalAlpaSebulan += $alpa;
            }
        }

        // ==========================================
        // 2. LOGIKA REAL-TIME CARD HARI INI
        // ==========================================
        $currentTime = $now->toTimeString();

        $namaJamKerja = 'Tidak ada jam kerja';
        $hadirCount = 0;
        $alpaCount = 0;
        $izinCutiSakitCount = 0;

        try {
            $isHariIniLibur = in_array($todayStr, $liburFormatted);

            if ($isHariIniLibur) {
                $dataLibur = \App\Models\HariLibur::whereDate('tanggal', $todayStr)->first();
                $namaJamKerja = $dataLibur ? $dataLibur->nama_keterangan : 'Hari Libur';
            } else {
                if (class_exists('\App\Models\Shift')) {
                    $shiftAktif = \App\Models\Shift::where(function ($query) use ($currentTime) {
                        $query->whereColumn('jam_masuk', '<=', 'jam_pulang')
                              ->where('jam_masuk', '<=', $currentTime)
                              ->where('jam_pulang', '>=', $currentTime);
                    })->orWhere(function ($query) use ($currentTime) {
                        $query->whereColumn('jam_masuk', '>', 'jam_pulang')
                              ->where(function ($subQuery) use ($currentTime) {
                                  $subQuery->where('jam_masuk', '<=', $currentTime)
                                           ->orWhere('jam_pulang', '>=', $currentTime);
                              });
                    })->first();

                    if ($shiftAktif) {
                        $jamMasukFormat = Carbon::parse($shiftAktif->jam_masuk)->format('H:i');
                        $jamPulangFormat = Carbon::parse($shiftAktif->jam_pulang)->format('H:i');
                        $namaJamKerja = $shiftAktif->nama_shift . " ({$jamMasukFormat} - {$jamPulangFormat})";

                        // 1. Ambil ID semua pegawai yang ditugaskan KHUSUS di shift ini
                        $userIdsDiShiftIni = \App\Models\User::where('role', 'pegawai')->where('shift_id', $shiftAktif->id)->pluck('id')->toArray();
                        $totalPegawaiShiftIni = count($userIdsDiShiftIni);

                        // 2. Filter Presensi HARI INI hanya untuk pegawai di shift ini
                        $presensiShiftIni = \App\Models\Presensi::whereDate('tanggal', $todayStr)
                                                    ->whereIn('user_id', $userIdsDiShiftIni)
                                                    ->get();

                        $hadirCount = $presensiShiftIni->whereIn('status', ['Hadir', 'hadir', 'Terlambat', 'terlambat'])->count();
                        $izinCutiSakitCount = $presensiShiftIni->whereIn('status', ['Izin', 'izin', 'Sakit', 'sakit', 'Cuti', 'cuti'])->count();

                        // 3. Logika Alpa (Delay 3 Jam)
                        $jamMasuk = Carbon::parse($shiftAktif->jam_masuk);
                        $batasWaktuAlpa = $jamMasuk->copy()->addHours(3);

                        // Sesuaikan batas alpa jika ini shift malam (lintas hari)
                        if ($shiftAktif->jam_masuk > $shiftAktif->jam_pulang && $currentTime >= '00:00:00' && $currentTime <= $shiftAktif->jam_pulang) {
                            $batasWaktuAlpa->subDay();
                        }

                        if ($now->greaterThanOrEqualTo($batasWaktuAlpa)) {
                            // Alpa dihitung dari total pegawai SHIFT INI, bukan total global
                            $alpaCount = $totalPegawaiShiftIni - ($hadirCount + $izinCutiSakitCount);
                            if ($alpaCount < 0) $alpaCount = 0;
                        }

                    } else {
                        $namaJamKerja = 'Di Luar Jam Kerja';
                    }
                }
            }
        } catch (\Exception $e) {
            $namaJamKerja = 'Gagal Cek Shift: Cek Kolom ID';
        }

        return view('admin.dashboard', [
            'selectedMonth' => $selectedMonth,
            'daftarBulan' => $daftarBulan,
            'namaBulanTerpilih' => $namaBulanTerpilih,
            'totalPegawai' => $totalPegawai,

            'totalHadir' => $totalHadirSebulan,
            'totalIzin' => $totalIzinSebulan,
            'totalAlpa' => $totalAlpaSebulan,
            'chartLabels' => $chartLabels,
            'chartHadir' => $chartHadir,
            'chartIzin' => $chartIzin,
            'chartAlpa' => $chartAlpa,

            'namaJamKerja' => $namaJamKerja,
            'hadirCount' => $hadirCount,
            'alpaCount' => $alpaCount,
            'izinCutiSakitCount' => $izinCutiSakitCount,
        ]);
    }
}
