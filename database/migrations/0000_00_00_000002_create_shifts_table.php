<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_shift');           // Contoh: Shift 1
            $table->time('jam_masuk');              // 15:00
            $table->time('jam_pulang');             // 23:00
            $table->time('toleransi_keterlambatan');// 15:15
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
