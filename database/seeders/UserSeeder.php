<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 akun Admin Master
        User::create([
            'id_pegawai' => 'MASTER-001',
            'name'       => 'Administrator Utama',
            'username'   => 'admin',
            'email'      => 'admin.absensi.sppg@gmail.com',
            'password'   => Hash::make('SPPG2026!'),
            'role'       => 'admin',
            'phone'      => '081393328852',
            'alamat'     => 'Kantor SPPG Langensari',
        ]);
    }
}
