<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            
            // Data Identitas Dasar
            $table->integer('no')->nullable();
            $table->string('kode_fingerprint', 50)->nullable()->index();
            $table->string('status_kepegawaian', 50)->nullable()->index();
            $table->string('jenis_kepegawaian', 100)->nullable();
            $table->string('nama_dengan_gelar')->comment('Nama lengkap dengan gelar');
            $table->string('nama_tanpa_gelar')->index()->comment('Nama tanpa gelar');
            $table->string('nip', 20)->unique()->comment('Nomor Induk Pegawai');
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('status_perkawinan', 50)->nullable();
            $table->integer('jumlah_anak')->default(0);
            $table->string('agama', 50)->nullable();
            
            // Data Jabatan
            $table->string('jabatan')->nullable()->index();
            $table->string('eselon', 20)->nullable();
            $table->integer('kelas_jabatan')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->string('nomor_sk_jabatan', 100)->nullable();
            $table->decimal('angka_kredit_sk', 10, 2)->nullable();
            $table->decimal('angka_kredit_jabatan_fungsional_terakhir', 10, 2)->nullable();
            $table->text('riwayat_jabatan_fungsional')->nullable();
            
            // Data Unit Kerja
            $table->string('unit_kerja_eselon_1')->nullable()->index();
            $table->string('unit_kerja_es_2')->nullable();
            $table->string('unit_kerja_es_3')->nullable();
            $table->string('unit_kerja_es_4')->nullable();
            
            // Data Pangkat
            $table->string('pangkat', 100)->nullable();
            $table->date('tmt_pangkat')->nullable();
            $table->date('naik_pangkat_berikutnya')->nullable();
            $table->string('sk_pangkat', 100)->nullable();
            
            // Data KGB
            $table->integer('kgb_tahun')->nullable();
            $table->integer('kgb_bulan')->nullable();
            
            // Data CPNS & Pensiun
            $table->date('tmt_cpns')->nullable();
            $table->date('tmt_pensiun')->nullable();
            $table->integer('tahun_pensiun')->nullable();
            
            // Data Pendidikan
            $table->string('pendidikan_pertama_saat_masuk_pns')->nullable();
            $table->text('riwayat_pendidikan_formal')->nullable();
            $table->string('pendidikan_terakhir', 100)->nullable();
            $table->string('jurusan')->nullable();
            $table->string('almamater')->nullable();
            $table->integer('tahun_lulus')->nullable();
            
            // Data Instansi
            $table->text('riwayat_instansi_pegawai')->nullable();
            $table->string('instansi_asal')->nullable();
            $table->string('instansi_induk')->nullable();
            
            // Data Alamat & Kontak
            $table->text('alamat_saat_ini')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->string('nik', 20)->nullable();
            
            // Data Keuangan
            $table->string('npwp', 30)->nullable();
            $table->string('no_rek_bni', 50)->nullable();
            $table->string('no_rek_bri', 50)->nullable();
            
            // Data Kontak
            $table->string('nomor_hp', 20)->nullable();
            
            // Data Operator
            $table->string('operator_keuangan', 50)->nullable();
            $table->string('operator_all_unit', 50)->nullable();
            
            // Metadata
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
