<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    // Menampilkan halaman Data Jabatan
    public function index()
    {
        // Ambil data dari database dan urutkan dari yang terbaru
        $positions = Jabatan::orderBy('id', 'desc')->get();

        return view('admin.master.jabatan.index', compact('positions'));
    }

    public function store(Request $request)
    {
        // 1. Validasi inputan dari form HTML
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        // 2. Simpan ke database
        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
        ]);

        // 3. Kembalikan halaman dengan pesan sukses
        return redirect()->back()->with('success', 'Data Jabatan berhasil ditambahkan!');
    }

    // Memperbarui data Jabatan dari Modal Edit
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255'
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan
        ]);

        return back()->with('success', 'Data Jabatan berhasil diperbarui!');
    }

    // Menghapus data Jabatan dari Modal Hapus
    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return back()->with('success', 'Data Jabatan berhasil dihapus!');
    }
}
