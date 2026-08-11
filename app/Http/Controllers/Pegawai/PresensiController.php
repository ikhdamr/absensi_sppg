<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function riwayat(Request $request)
    {
        // Ambil filter bulan dan tahun dari URL (jika tidak ada, gunakan bulan & tahun saat ini)
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Ambil data presensi khusus untuk pegawai yang sedang login
        $presensi = Presensi::where('user_id', Auth::id())
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->orderBy('tanggal', 'desc')
                    ->get();

        return view('pegawai.riwayat-presensi.index', compact('presensi', 'bulan', 'tahun'));
    }
}
