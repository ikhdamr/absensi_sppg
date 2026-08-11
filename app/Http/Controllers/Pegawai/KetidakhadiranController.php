<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ketidakhadiran;

class KetidakhadiranController extends Controller
{
    /**
     * Menampilkan halaman form pengajuan & riwayat ketidakhadiran
     */
    public function index()
    {
        // Ambil data riwayat hanya milik pegawai yang sedang login
        $riwayat = Ketidakhadiran::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('pegawai.ketidakhadiran', compact('riwayat'));
    }

    /**
     * Memproses dan menyimpan form pengajuan
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari form HTML
        $request->validate([
            'kategori'     => 'required|in:Izin,Sakit,Cuti',
            'tanggal_izin' => 'required|date',
            'deskripsi'    => 'required|string',
            'file_bukti'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ], [
            // Kustomisasi pesan error
            'file_bukti.image' => 'File bukti harus berupa gambar (JPG/PNG).',
            'file_bukti.max'   => 'Ukuran file bukti maksimal adalah 2MB.'
        ]);

        // 2. Proses Upload File Bukti (Misal: Surat Dokter) - Jika Ada
        $pathBukti = null;
        if ($request->hasFile('file_bukti')) {
            // Simpan ke folder storage/app/public/bukti_ketidakhadiran
            $pathBukti = $request->file('file_bukti')->store('bukti_ketidakhadiran', 'public');
        }

        // 3. Simpan ke Database
        Ketidakhadiran::create([
            'user_id'      => Auth::id(),
            'kategori'     => $request->kategori,
            'tanggal_izin' => $request->tanggal_izin,
            'deskripsi'    => $request->deskripsi,
            'file_bukti'   => $pathBukti,
            'status'       => 'Menunggu', // Sesuai dengan enum database Anda
        ]);

        // 4. Redirect kembali dengan Alert Sukses Hijau
        return redirect()->back()->with('success', 'Pengajuan ' . $request->kategori . ' berhasil dikirim dan sedang menunggu persetujuan Admin.');
    }
    /**
     * Menampilkan halaman Edit Pengajuan
     */
    public function edit(string $id)
    {
        $pengajuan = Ketidakhadiran::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Jika statusnya sudah diproses, tolak akses edit
        if($pengajuan->status != 'Menunggu') {
            return redirect()->route('pegawai.dashboard')->withErrors(['gagal' => 'Pengajuan sudah diproses dan tidak dapat diubah.']);
        }

        return view('pegawai.edit_ketidakhadiran', compact('pengajuan'));
    }

    /**
     * Memproses update/perubahan pengajuan
     */
    public function update(Request $request, string $id)
    {
        $pengajuan = Ketidakhadiran::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'kategori'     => 'required|in:Izin,Sakit,Cuti',
            'tanggal_izin' => 'required|date',
            'deskripsi'    => 'required|string',
            'file_bukti'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pathBukti = $pengajuan->file_bukti; // Gunakan file lama sebagai default
        if ($request->hasFile('file_bukti')) {
            $pathBukti = $request->file('file_bukti')->store('bukti_ketidakhadiran', 'public');
        }

        $pengajuan->update([
            'kategori'     => $request->kategori,
            'tanggal_izin' => $request->tanggal_izin,
            'deskripsi'    => $request->deskripsi,
            'file_bukti'   => $pathBukti,
        ]);

        return redirect()->route('pegawai.dashboard')->with('success', 'Data pengajuan berhasil diperbarui!');
    }

    /**
     * Membatalkan / Menghapus Pengajuan
     */
    public function destroy(string $id)
    {
        $pengajuan = Ketidakhadiran::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if($pengajuan->status == 'Menunggu') {
            $pengajuan->delete();
            return redirect()->back()->with('success', 'Pengajuan ketidakhadiran berhasil dibatalkan.');
        }

        return redirect()->back()->withErrors(['gagal' => 'Pengajuan yang sudah diproses tidak bisa dibatalkan.']);
    }
}
