<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya benar
    protected $table = 'jabatans';

    // Buka kunci kolom agar bisa diisi
    protected $fillable = [
        'nama_jabatan'
    ];
}
