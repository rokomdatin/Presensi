<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_jam_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jadwal', 100);
            $table->time('jam_masuk')->default('08:00:00');
            $table->time('jam_keluar')->default('17:00:00');
            $table->integer('toleransi_terlambat')->default(15)->comment('Toleransi dalam menit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_jam_kerja');
    }
};
