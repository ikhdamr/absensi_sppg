<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::query()->latest()->get();
        return view('admin.master.jam_kerja.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_keterlambatan' => 'required', // <-- Ubah di validasi
        ]);

        Shift::create([
            'nama_shift' => $request->nama_shift,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'toleransi_keterlambatan' => $request->toleransi_keterlambatan, // <-- Ubah saat simpan
        ]);

        return redirect()->back()->with('success', 'Jam Kerja berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'toleransi_keterlambatan' => 'required',
        ]);

        $shift = Shift::findOrFail($id);

        $shift->update([
            'nama_shift' => $request->nama_shift,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'toleransi_keterlambatan' => $request->toleransi_keterlambatan,
        ]);

        return redirect()->back()->with('success', 'Jam Kerja berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        Shift::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jam Kerja berhasil dihapus!');
    }
}
