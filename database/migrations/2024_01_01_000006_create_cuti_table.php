<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->enum('jenis_cuti', ['tahunan', 'sakit', 'melahirkan', 'besar', 'penting', 'luar_tanggungan']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            $table->text('alasan')->nullable();
            $table->string('dokumen_pendukung')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->timestamps();
            
            $table->index(['pegawai_id', 'status']);
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};
