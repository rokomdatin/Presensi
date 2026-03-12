<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\PengaturanJamKerja;
use App\Models\HariLibur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default users
        User::create([
            'username' => 'admin',
            'email' => 'admin@kemenkopm.go.id',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Administrator',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'username' => 'kepegawaian',
            'email' => 'kepegawaian@kemenkopm.go.id',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Staff Kepegawaian',
            'role' => 'kepegawaian',
            'is_active' => true,
        ]);

        User::create([
            'username' => 'keuangan',
            'email' => 'keuangan@kemenkopm.go.id',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Staff Keuangan',
            'role' => 'keuangan',
            'is_active' => true,
        ]);

        User::create([
            'username' => 'guest',
            'email' => 'guest@kemenkopm.go.id',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Guest User',
            'role' => 'guest',
            'nip' => '198501152010011001',
            'is_active' => true,
        ]);

        // Create default jam kerja
        PengaturanJamKerja::create([
            'nama_jadwal' => 'Reguler',
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '17:00:00',
            'toleransi_terlambat' => 15,
            'is_active' => true,
        ]);

        PengaturanJamKerja::create([
            'nama_jadwal' => 'Ramadhan',
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '15:30:00',
            'toleransi_terlambat' => 15,
            'is_active' => false,
        ]);

        // Create sample hari libur 2025
        $hariLibur = [
            ['tanggal' => '2025-01-01', 'nama_libur' => 'Tahun Baru Masehi'],
            ['tanggal' => '2025-01-29', 'nama_libur' => 'Tahun Baru Imlek'],
            ['tanggal' => '2025-03-29', 'nama_libur' => 'Hari Raya Nyepi'],
            ['tanggal' => '2025-03-31', 'nama_libur' => 'Idul Fitri'],
            ['tanggal' => '2025-04-01', 'nama_libur' => 'Idul Fitri'],
            ['tanggal' => '2025-05-01', 'nama_libur' => 'Hari Buruh'],
            ['tanggal' => '2025-08-17', 'nama_libur' => 'Hari Kemerdekaan RI'],
            ['tanggal' => '2025-12-25', 'nama_libur' => 'Hari Raya Natal'],
        ];

        foreach ($hariLibur as $libur) {
            HariLibur::create($libur);
        }

        // Create sample pegawai
        Pegawai::create([
            'no' => 1,
            'kode_fingerprint' => 'FP001',
            'status_kepegawaian' => 'PNS',
            'jenis_kepegawaian' => 'Struktural',
            'nama_dengan_gelar' => 'Dr. Budi Santoso, S.Kom., M.T.',
            'nama_tanpa_gelar' => 'Budi Santoso',
            'nip' => '198501152010011001',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-01-15',
            'jenis_kelamin' => 'Laki-laki',
            'status_perkawinan' => 'Menikah',
            'jumlah_anak' => 2,
            'agama' => 'Islam',
            'jabatan' => 'Kepala Bagian Umum',
            'eselon' => 'III.A',
            'kelas_jabatan' => 12,
            'unit_kerja_eselon_1' => 'Kementerian Koordinator Pemberdayaan Masyarakat',
            'unit_kerja_es_2' => 'Sekretariat',
            'pangkat' => 'Pembina',
            'tmt_pangkat' => '2022-04-01',
            'pendidikan_terakhir' => 'S3',
            'jurusan' => 'Teknik Informatika',
            'almamater' => 'Universitas Indonesia',
            'nik' => '3201011501850001',
            'nomor_hp' => '081234567890',
            'is_active' => true,
        ]);

        Pegawai::create([
            'no' => 2,
            'kode_fingerprint' => 'FP002',
            'status_kepegawaian' => 'PNS',
            'jenis_kepegawaian' => 'Fungsional',
            'nama_dengan_gelar' => 'Siti Rahayu, S.E., M.M.',
            'nama_tanpa_gelar' => 'Siti Rahayu',
            'nip' => '199003202015012001',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-03-20',
            'jenis_kelamin' => 'Perempuan',
            'status_perkawinan' => 'Menikah',
            'jumlah_anak' => 1,
            'agama' => 'Islam',
            'jabatan' => 'Analis Kebijakan',
            'kelas_jabatan' => 9,
            'unit_kerja_eselon_1' => 'Kementerian Koordinator Pemberdayaan Masyarakat',
            'unit_kerja_es_2' => 'Deputi Bidang Ekonomi',
            'pangkat' => 'Penata Muda Tk.I',
            'tmt_pangkat' => '2023-04-01',
            'pendidikan_terakhir' => 'S2',
            'jurusan' => 'Manajemen',
            'almamater' => 'Universitas Padjadjaran',
            'is_active' => true,
        ]);

        Pegawai::create([
            'no' => 3,
            'kode_fingerprint' => 'FP003',
            'status_kepegawaian' => 'PPPK',
            'jenis_kepegawaian' => 'Fungsional',
            'nama_dengan_gelar' => 'Ahmad Fadli, S.T.',
            'nama_tanpa_gelar' => 'Ahmad Fadli',
            'nip' => '199505102022011001',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1995-05-10',
            'jenis_kelamin' => 'Laki-laki',
            'status_perkawinan' => 'Belum Menikah',
            'jumlah_anak' => 0,
            'agama' => 'Islam',
            'jabatan' => 'Pranata Komputer',
            'kelas_jabatan' => 8,
            'unit_kerja_eselon_1' => 'Kementerian Koordinator Pemberdayaan Masyarakat',
            'unit_kerja_es_2' => 'Biro TIK',
            'pangkat' => 'Penata Muda',
            'tmt_pangkat' => '2022-01-01',
            'pendidikan_terakhir' => 'S1',
            'jurusan' => 'Teknik Informatika',
            'almamater' => 'Institut Teknologi Sepuluh Nopember',
            'is_active' => true,
        ]);
    }
}
