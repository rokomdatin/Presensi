<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->integer('tahun');
            $table->integer('saldo_awal')->default(12);
            $table->integer('saldo_terpakai')->default(0);
            $table->integer('saldo_sisa')->default(12);
            $table->timestamps();
            
            $table->unique(['pegawai_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_cuti');
    }
};
