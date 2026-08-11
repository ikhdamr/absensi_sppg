<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts'; // Pastikan nama tabelnya sesuai

    // Buka kunci kolom agar bisa diisi
    protected $fillable = [
        'nama_shift',
        'jam_masuk',
        'jam_pulang',
        'toleransi_keterlambatan',
    ];
}
