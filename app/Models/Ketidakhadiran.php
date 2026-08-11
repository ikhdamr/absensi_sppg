<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketidakhadiran extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya sesuai
    protected $table = 'ketidakhadirans';

    // WAJIB: Daftarkan kolom mana saja yang boleh diisi (di-insert) ke database
    protected $fillable = [
        'user_id',
        'kategori',
        'tanggal_izin',
        'deskripsi',
        'file_bukti',
        'status',
    ];

    // Relasi balik ke tabel User (Pegawai)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
