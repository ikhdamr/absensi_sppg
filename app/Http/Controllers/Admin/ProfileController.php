<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHAN BARU DI SINI
use App\Models\User;

class ProfileController extends Controller
{
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

        // Update password JIKA DIISI
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
