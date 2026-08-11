<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // <-- Ditambahkan untuk menembak API
use Carbon\Carbon; // <-- Ditambahkan untuk memanipulasi waktu

class HariLiburController extends Controller
{
    // Menampilkan halaman Data Hari Libur
    public function index()
    {
        $holidays = HariLibur::orderBy('tanggal', 'desc')->get();
        return view('admin.master.hari_libur.index', compact('holidays'));
    }

    // =========================================================
    // FITUR BARU: TARIK OTOMATIS DATA LIBUR NASIONAL API (NAGER.DATE)
    // =========================================================
    public function syncLiburNasional()
    {
        try {
            $tahunIni = Carbon::now()->format('Y');

            // Menggunakan Nager.Date API (Server Global Internasional yang sangat stabil)
            // Kita langsung menembak endpoint khusus negara Indonesia (ID) di tahun saat ini
            $response = Http::withoutVerifying()
                ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64)')
                ->timeout(15)
                ->get("https://date.nager.at/api/v3/PublicHolidays/{$tahunIni}/ID");

            if ($response->successful()) {
                $holidays = $response->json();
                $count = 0;

                foreach ($holidays as $libur) {
                    $tanggal = $libur['date']; // Format: YYYY-MM-DD
                    $keterangan = $libur['localName']; // Mengambil nama libur dalam Bahasa Indonesia

                    // Cek database agar tidak terjadi duplikat data
                    $exists = HariLibur::where('tanggal', $tanggal)->exists();

                    if (!$exists) {
                        HariLibur::create([
                            'tanggal' => $tanggal,
                            'keterangan' => $keterangan
                        ]);
                        $count++;
                    }
                }

                if ($count > 0) {
                    return back()->with('success', "Berhasil menarik $count data Hari Libur Nasional baru untuk tahun $tahunIni!");
                } else {
                    return back()->with('success', 'Semua hari libur nasional tahun ini sudah tersinkronisasi (Tidak ada data baru).');
                }
            } else {
                return back()->withErrors(['gagal' => 'API Error Code: ' . $response->status() . ' - Gagal mengambil data dari server Global.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['gagal' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    // Menyimpan data Hari Libur baru manual
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
        ], [
            'tanggal.required' => 'Tanggal libur wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'keterangan.required' => 'Keterangan atau nama libur wajib diisi.',
        ]);

        HariLibur::create([
            'tanggal' => $request->input('tanggal'),
            'keterangan' => $request->input('keterangan'),
        ]);

        return redirect()->back()->with('success', 'Data hari libur berhasil disimpan!');
    }

    // Memperbarui data Hari Libur
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255'
        ]);

        $hariLibur = HariLibur::findOrFail($id);
        $hariLibur->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return back()->with('success', 'Hari Libur berhasil diperbarui!');
    }

    // Menghapus data Hari Libur
    public function destroy(string $id)
    {
        $hariLibur = HariLibur::findOrFail($id);
        $hariLibur->delete();

        return back()->with('success', 'Hari Libur berhasil dihapus!');
    }
}
