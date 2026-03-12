<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->date('tanggal')->index();
            
            // Clock In
            $table->time('waktu_masuk')->nullable();
            $table->string('lokasi_masuk')->nullable();
            $table->decimal('lat_masuk', 10, 8)->nullable();
            $table->decimal('lng_masuk', 11, 8)->nullable();
            $table->string('foto_masuk')->nullable();
            $table->enum('metode_masuk', ['fingerprint', 'manual', 'mobile', 'web'])->default('manual');
            
            // Clock Out
            $table->time('waktu_keluar')->nullable();
            $table->string('lokasi_keluar')->nullable();
            $table->decimal('lat_keluar', 10, 8)->nullable();
            $table->decimal('lng_keluar', 11, 8)->nullable();
            $table->string('foto_keluar')->nullable();
            $table->enum('metode_keluar', ['fingerprint', 'manual', 'mobile', 'web'])->default('manual');
            
            // Status & Keterangan
            $table->enum('status', ['hadir', 'izin', 'sakit', 'cuti', 'dinas_luar', 'tanpa_keterangan', 'libur'])->default('hadir')->index();
            $table->text('keterangan')->nullable();
            
            // Durasi Kerja
            $table->integer('durasi_kerja')->nullable()->comment('Durasi kerja dalam menit');
            
            // Keterlambatan
            $table->boolean('terlambat')->default(false);
            $table->integer('menit_terlambat')->default(0);
            
            // Pulang Cepat
            $table->boolean('pulang_cepat')->default(false);
            $table->integer('menit_pulang_cepat')->default(0);
            
            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Unique: 1 pegawai 1 absensi per hari
            $table->unique(['pegawai_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
