<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    // TAMBAHKAN BARIS INI: Agar Laravel tidak mencari tabel bernama "presensis"
    protected $table = 'presensis';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'menit_terlambat'
    ];

    // Relasi: Satu data presensi ini milik satu User (Pegawai)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
