<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'no',
        'kode_fingerprint',
        'status_kepegawaian',
        'jenis_kepegawaian',
        'nama_dengan_gelar',
        'nama_tanpa_gelar',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'jumlah_anak',
        'agama',
        'jabatan',
        'eselon',
        'kelas_jabatan',
        'tanggal_sk',
        'tmt_jabatan',
        'nomor_sk_jabatan',
        'angka_kredit_sk',
        'angka_kredit_jabatan_fungsional_terakhir',
        'riwayat_jabatan_fungsional',
        'unit_kerja_eselon_1',
        'unit_kerja_es_2',
        'unit_kerja_es_3',
        'unit_kerja_es_4',
        'pangkat',
        'tmt_pangkat',
        'naik_pangkat_berikutnya',
        'sk_pangkat',
        'kgb_tahun',
        'kgb_bulan',
        'tmt_cpns',
        'tmt_pensiun',
        'tahun_pensiun',
        'pendidikan_pertama_saat_masuk_pns',
        'riwayat_pendidikan_formal',
        'pendidikan_terakhir',
        'jurusan',
        'almamater',
        'tahun_lulus',
        'riwayat_instansi_pegawai',
        'instansi_asal',
        'instansi_induk',
        'alamat_saat_ini',
        'alamat_ktp',
        'nik',
        'npwp',
        'no_rek_bni',
        'no_rek_bri',
        'nomor_hp',
        'operator_keuangan',
        'operator_all_unit',
        'foto',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_sk' => 'date',
        'tmt_jabatan' => 'date',
        'tmt_pangkat' => 'date',
        'naik_pangkat_berikutnya' => 'date',
        'tmt_cpns' => 'date',
        'tmt_pensiun' => 'date',
        'is_active' => 'boolean',
        'jumlah_anak' => 'integer',
        'kelas_jabatan' => 'integer',
        'kgb_tahun' => 'integer',
        'kgb_bulan' => 'integer',
        'tahun_pensiun' => 'integer',
        'tahun_lulus' => 'integer',
    ];

    // Kolom untuk role Kepegawaian (exclude keuangan)
    public static function kolom_kepegawaian(): array
    {
        return [
            'id', 'no', 'kode_fingerprint', 'status_kepegawaian', 'jenis_kepegawaian',
            'nama_dengan_gelar', 'nama_tanpa_gelar', 'nip', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'status_perkawinan', 'jumlah_anak', 'agama', 'jabatan', 'eselon',
            'kelas_jabatan', 'tanggal_sk', 'tmt_jabatan', 'nomor_sk_jabatan', 'angka_kredit_sk',
            'angka_kredit_jabatan_fungsional_terakhir', 'riwayat_jabatan_fungsional',
            'unit_kerja_eselon_1', 'unit_kerja_es_2', 'unit_kerja_es_3', 'unit_kerja_es_4',
            'pangkat', 'tmt_pangkat', 'naik_pangkat_berikutnya', 'sk_pangkat',
            'tmt_cpns', 'tmt_pensiun', 'tahun_pensiun',
            'pendidikan_pertama_saat_masuk_pns', 'riwayat_pendidikan_formal',
            'pendidikan_terakhir', 'jurusan', 'almamater', 'tahun_lulus',
            'riwayat_instansi_pegawai', 'instansi_asal', 'instansi_induk',
            'alamat_saat_ini', 'alamat_ktp', 'nik', 'nomor_hp', 'foto', 'is_active'
        ];
    }

    // Kolom untuk role Keuangan
    public static function kolom_keuangan(): array
    {
        return [
            'id', 'nama_dengan_gelar', 'nama_tanpa_gelar', 'nip', 'jabatan',
            'unit_kerja_es_2', 'npwp', 'no_rek_bni', 'no_rek_bri',
            'kgb_tahun', 'kgb_bulan', 'operator_keuangan'
        ];
    }

    // Kolom untuk role Guest (data pribadi)
    public static function kolom_guest(): array
    {
        return [
            'id', 'nama_dengan_gelar', 'nama_tanpa_gelar', 'nip', 'tempat_lahir',
            'tanggal_lahir', 'jenis_kelamin', 'jabatan', 'unit_kerja_eselon_1',
            'unit_kerja_es_2', 'pangkat', 'pendidikan_terakhir', 'nomor_hp',
            'alamat_saat_ini', 'foto'
        ];
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function cuti()
    {
        return $this->hasMany(Cuti::class);
    }

    public function saldoCuti()
    {
        return $this->hasMany(SaldoCuti::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAsn($query)
    {
        return $query->whereIn('status_kepegawaian', ['PNS', 'PPPK']);
    }

    public function scopePns($query)
    {
        return $query->where('status_kepegawaian', 'PNS');
    }

    public function scopePppk($query)
    {
        return $query->where('status_kepegawaian', 'PPPK');
    }
}
