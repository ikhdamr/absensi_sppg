<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    use HasFactory;

    // WAJIB DITAMBAHKAN: Kasih tahu Laravel nama tabel aslinya
    protected $table = 'hari_liburs';

    protected $fillable = [
        'tanggal',
        'keterangan',
    ];
}
