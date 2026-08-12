<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * 1. FUNGSI UNTUK UPDATE PROFIL ADMIN
     */
    public function update(Request $request)
    {
        // Gunakan Facade Auth agar Intelephense VS Code tidak error
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'phone'    => 'required|string',
            'alamat'   => 'required|string',
            'password' => 'nullable|string|min:6',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Proses Foto jika ada yang diupload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path('uploads/pegawai/' . $user->photo))) {
                @unlink(public_path('uploads/pegawai/' . $user->photo));
            }
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/pegawai'), $photoName);
            $user->photo = $photoName;
        }

        // Update data text
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->phone    = $request->phone;
        $user->alamat   = $request->alamat;

        // Update password JIKA DIISI (Dari form edit profil)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * 2. FUNGSI KHUSUS UNTUK UBAH PASSWORD (DARI MODAL UBAH PASSWORD)
     */
    public function updatePassword(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', 
        ], [
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Cek apakah password lama yang dimasukkan benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama yang Anda masukkan salah!');
        }

        // 3. Simpan Password Baru (Otomatis di-hash)
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // 4. Proses Logout (Keluarkan user demi keamanan)
        Auth::logout();
        
        // Hapus sesi saat ini agar benar-benar bersih
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 5. Arahkan ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Password berhasil diubah! Silakan login kembali dengan password baru Anda.');
    }
}