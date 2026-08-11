<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Shift;
use Illuminate\Support\Facades\Hash;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    // ====================================
    // 1. MENAMPILKAN DATA PEGAWAI (DENGAN PENCARIAN)
    // ====================================
    public function index(Request $request)
    {
        $bulanIni = \Carbon\Carbon::now()->format('m');
        $tahunIni = \Carbon\Carbon::now()->format('Y');

        // 1. Ambil keyword pencarian dari URL (jika ada)
        $search = $request->input('search');

        // 2. Buat Query Dasar (Semua tampil KECUALI Administrator Utama / ID 1)
        $query = User::with(['shift', 'jabatan'])
                     ->where('name', '!=', 'Administrator Utama')
                     ->where('id', '!=', 1);

        // 3. Jika ada input pencarian, filter berdasarkan Nama ATAU ID Pegawai
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id_pegawai', 'like', "%{$search}%");
            });
        }

        // 4. Eksekusi Query dan Hitung Presensi
        $pegawai = $query->get()->map(function ($p) use ($bulanIni, $tahunIni) {
            $presensi = \App\Models\Presensi::query()
                ->where('user_id', '=', $p->id)
                ->whereMonth('tanggal', '=', $bulanIni)
                ->whereYear('tanggal', '=', $tahunIni)
                ->get();

            $p->setAttribute('total_hadir', $presensi->whereIn('status', ['Hadir', 'hadir', 'Terlambat', 'terlambat'])->count());
            $p->setAttribute('total_izin', $presensi->whereIn('status', ['Izin', 'izin', 'Sakit', 'sakit', 'Cuti', 'cuti'])->count());
            $p->setAttribute('total_alpa', $presensi->whereIn('status', ['Alpa', 'alpa'])->count());
            $p->setAttribute('total_late', $presensi->filter(function ($item) {
                return in_array(strtolower($item->status), ['terlambat']) || $item->menit_terlambat > 0;
            })->count());

            return $p;
        });

        $positions = \App\Models\Jabatan::orderBy('nama_jabatan', 'asc')->get();
        $shifts = \App\Models\Shift::orderBy('nama_shift', 'asc')->get();

        // 5. Kirim variabel $search ke tampilan agar kotak input tidak kosong setelah mencari
        return view('admin.pegawai.index', compact('pegawai', 'positions', 'shifts', 'search'));
    }

    // ====================================
    // 2. PROSES TAMBAH PEGAWAI BARU
    // ====================================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'jabatan_id' => 'required',
            'shift_id'   => 'required',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'username'   => 'required|string|max:255|unique:users,username',
            'password'   => 'required|string|min:6',
            'role'       => 'required|in:admin,pegawai',
            'photo'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            // --- TERJEMAHAN BAHASA INDONESIA ---
            'photo.max'       => 'Ukuran foto profil tidak boleh lebih dari 2MB.',
            'photo.image'     => 'File yang diunggah harus berupa gambar.',
            'photo.mimes'     => 'Format foto harus berupa jpeg, png, atau jpg.',
            'email.unique'    => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'username.unique' => 'Username ini sudah dipakai, silakan cari username lain.',
            'password.min'    => 'Password terlalu pendek, minimal 6 karakter.',
            'required'        => 'Bagian ini wajib diisi dan tidak boleh dibiarkan kosong.'
        ]);

        if ($validator->fails()) {
            return redirect('/admin/pegawai')->withErrors($validator)->withInput();
        }

        // --- LOGIKA GENERATE ID OTOMATIS ---
        $lastPegawai = User::query()->where('role', '=', 'pegawai')->orderBy('id', 'desc')->first();

        // UBAH nomor_identitas menjadi id_pegawai
        if ($lastPegawai && $lastPegawai->id_pegawai) {
            $lastNumber = intval(preg_replace('/[^0-9]/', '', $lastPegawai->id_pegawai));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $idPegawaiOtomatis = 'SPPG-LGSR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        // -----------------------------------

        $photoName = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/pegawai'), $photoName);
        }

        User::create([
            'id_pegawai'      => $idPegawaiOtomatis,
            'name'            => $request->name,
            'jabatan_id'      => $request->jabatan_id,
            'shift_id'        => $request->shift_id,
            'email'           => $request->email,
            'username'        => $request->username,
            'password'        => Hash::make($request->password),
            'role'            => $request->role,
            'photo'           => $photoName,
            'phone'           => $request->phone,
            'alamat'          => $request->alamat,
        ]);

        return redirect('/admin/pegawai')->with('success', 'Pegawai berhasil ditambahkan dengan ID: ' . $idPegawaiOtomatis);
    }

    // ====================================
    // 3. PROSES EDIT / UPDATE DATA PEGAWAI
    // ====================================
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Validasi HANYA untuk Jabatan, Shift, dan Role
        $validator = Validator::make($request->all(), [
            'jabatan_id' => 'required',
            'shift_id'   => 'required',
            'role'       => 'required|in:admin,pegawai',
        ], [
            'required' => 'Bagian ini wajib dipilih.'
        ]);

        if ($validator->fails()) {
            return redirect('/admin/pegawai')->withErrors($validator)->withInput();
        }

        // Update HANYA 3 kolom tersebut ke database
        $user->update([
            'jabatan_id' => $request->jabatan_id,
            'shift_id'   => $request->shift_id,
            'role'       => $request->role,
        ]);

        return redirect('/admin/pegawai')->with('success', 'Hak akses, shift, dan jabatan pegawai berhasil diperbarui!');
    }

    // ====================================
    // 4. HAPUS PEGAWAI
    // ====================================
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->photo && file_exists(public_path('uploads/pegawai/' . $user->photo))) {
            @unlink(public_path('uploads/pegawai/' . $user->photo));
        }
        $user->delete();

        return redirect('/admin/pegawai')->with('success', 'Data pegawai berhasil dihapus!');
    }

    // ====================================
    // 5. RESET PASSWORD PEGAWAI
    // ====================================
    public function resetPassword(string $id)
    {
        $user = User::findOrFail($id);

        // Ubah password kembali ke default: SPPG2026!
        $user->update([
            'password' => Hash::make('SPPG2026!')
        ]);

        return redirect('/admin/pegawai')->with('success', 'Password milik ' . $user->name . ' berhasil direset menjadi: SPPG2026!');
    }
}
