<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\ImportLog;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Log;

class PegawaiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected $importLog;
    protected $rowCount = 0;
    protected $successCount = 0;
    protected $failedCount = 0;
    protected $errors = [];

    public function __construct(ImportLog $importLog)
    {
        $this->importLog = $importLog;
    }

    public function model(array $row)
    {
        $this->rowCount++;

        try {
            // Mapping kolom Excel ke database
            $pegawai = Pegawai::updateOrCreate(
                ['nip' => $row['nip'] ?? $row['NIP'] ?? null],
                [
                    'no' => $row['no'] ?? $row['NO'] ?? null,
                    'kode_fingerprint' => $row['kode_fingerprint'] ?? $row['KODE FINGERPRINT'] ?? null,
                    'status_kepegawaian' => $row['status_kepegawaian'] ?? $row['STATUS KEPEGAWAIAN'] ?? null,
                    'jenis_kepegawaian' => $row['jenis_kepegawaian'] ?? $row['JENIS KEPEGAWAIAN'] ?? null,
                    'nama_dengan_gelar' => $row['nama_dengan_gelar'] ?? $row['NAMA DENGAN GELAR'] ?? null,
                    'nama_tanpa_gelar' => $row['nama_tanpa_gelar'] ?? $row['NAMA TANPA GELAR'] ?? null,
                    'tempat_lahir' => $row['tempat_lahir'] ?? $row['TEMPAT LAHIR'] ?? null,
                    'tanggal_lahir' => $this->parseDate($row['tanggal_lahir'] ?? $row['TANGGAL LAHIR'] ?? null),
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? $row['JENIS KELAMIN'] ?? null,
                    'status_perkawinan' => $row['status_perkawinan'] ?? $row['STATUS PERKAWINAN'] ?? null,
                    'jumlah_anak' => $row['jumlah_anak'] ?? $row['JUMLAH ANAK'] ?? 0,
                    'agama' => $row['agama'] ?? $row['AGAMA'] ?? null,
                    'jabatan' => $row['jabatan'] ?? $row['JABATAN'] ?? null,
                    'eselon' => $row['eselon'] ?? $row['ESELON'] ?? null,
                    'kelas_jabatan' => $row['kelas_jabatan'] ?? $row['KELAS JABATAN'] ?? null,
                    'tanggal_sk' => $this->parseDate($row['tanggal_sk'] ?? $row['TANGGAL SK'] ?? null),
                    'tmt_jabatan' => $this->parseDate($row['tmt_jabatan'] ?? $row['TMT JABATAN'] ?? null),
                    'nomor_sk_jabatan' => $row['nomor_sk_jabatan'] ?? $row['NOMOR SK JABATAN'] ?? null,
                    'angka_kredit_sk' => $row['angka_kredit_sk'] ?? $row['ANGKA KREDIT SK'] ?? null,
                    'angka_kredit_jabatan_fungsional_terakhir' => $row['angka_kredit_jabatan_fungsional_terakhir'] ?? $row['ANGKA KREDIT JABATAN FUNGSIONAL TERAKHIR'] ?? null,
                    'riwayat_jabatan_fungsional' => $row['riwayat_jabatan_fungsional'] ?? $row['RIWAYAT JABATAN FUNGSIONAL'] ?? null,
                    'unit_kerja_eselon_1' => $row['unit_kerja_eselon_1'] ?? $row['UNIT KERJA ESELON 1'] ?? null,
                    'unit_kerja_es_2' => $row['unit_kerja_es_2'] ?? $row['UNIT KERJA ES 2'] ?? null,
                    'unit_kerja_es_3' => $row['unit_kerja_es_3'] ?? $row['UNIT KERJA ES 3'] ?? null,
                    'unit_kerja_es_4' => $row['unit_kerja_es_4'] ?? $row['UNIT KERJA ES 4'] ?? null,
                    'pangkat' => $row['pangkat'] ?? $row['PANGKAT'] ?? null,
                    'tmt_pangkat' => $this->parseDate($row['tmt_pangkat'] ?? $row['TMT PANGKAT'] ?? null),
                    'naik_pangkat_berikutnya' => $this->parseDate($row['naik_pangkat_berikutnya'] ?? $row['NAIK PANGKAT BERIKUTNYA'] ?? null),
                    'sk_pangkat' => $row['sk_pangkat'] ?? $row['SK PANGKAT'] ?? null,
                    'kgb_tahun' => $row['kgb_tahun'] ?? $row['KGB TAHUN'] ?? null,
                    'kgb_bulan' => $row['kgb_bulan'] ?? $row['KGB BULAN'] ?? null,
                    'tmt_cpns' => $this->parseDate($row['tmt_cpns'] ?? $row['TMT CPNS'] ?? null),
                    'tmt_pensiun' => $this->parseDate($row['tmt_pensiun'] ?? $row['TMT PENSIUN'] ?? null),
                    'tahun_pensiun' => $row['tahun_pensiun'] ?? $row['TAHUN PENSIUN'] ?? null,
                    'pendidikan_pertama_saat_masuk_pns' => $row['pendidikan_pertama_saat_masuk_pns'] ?? $row['PENDIDIKAN PERTAMA SAAT MASUK PNS'] ?? null,
                    'riwayat_pendidikan_formal' => $row['riwayat_pendidikan_formal'] ?? $row['RIWAYAT PENDIDIKAN FORMAL'] ?? null,
                    'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? $row['PENDIDIKAN TERAKHIR'] ?? null,
                    'jurusan' => $row['jurusan'] ?? $row['JURUSAN'] ?? null,
                    'almamater' => $row['almamater'] ?? $row['ALMAMATER'] ?? null,
                    'tahun_lulus' => $row['tahun_lulus'] ?? $row['TAHUN LULUS'] ?? null,
                    'riwayat_instansi_pegawai' => $row['riwayat_instansi_pegawai'] ?? $row['RIWAYAT INSTANSI PEGAWAI'] ?? null,
                    'instansi_asal' => $row['instansi_asal'] ?? $row['INSTANSI ASAL'] ?? null,
                    'instansi_induk' => $row['instansi_induk'] ?? $row['INSTANSI INDUK'] ?? null,
                    'alamat_saat_ini' => $row['alamat_saat_ini'] ?? $row['ALAMAT SAAT INI'] ?? null,
                    'alamat_ktp' => $row['alamat_ktp'] ?? $row['ALAMAT KTP'] ?? null,
                    'nik' => $row['nik'] ?? $row['NIK'] ?? null,
                    'npwp' => $row['npwp'] ?? $row['NPWP'] ?? null,
                    'no_rek_bni' => $row['no_rek_bni'] ?? $row['NO.REK BNI'] ?? null,
                    'no_rek_bri' => $row['no_rek_bri'] ?? $row['NO.REK BRI'] ?? null,
                    'nomor_hp' => $row['nomor_hp'] ?? $row['NOMOR HP'] ?? null,
                    'operator_keuangan' => $row['operator_keuangan'] ?? $row['OPERATOR KEUANGAN'] ?? null,
                    'operator_all_unit' => $row['operator_all_unit'] ?? $row['OPERATOR ALL UNIT'] ?? null,
                    'is_active' => true,
                    'created_by' => auth()->id(),
                ]
            );

            $this->successCount++;
            return $pegawai;

        } catch (\Exception $e) {
            $this->failedCount++;
            $this->errors[] = [
                'row' => $this->rowCount,
                'nip' => $row['nip'] ?? $row['NIP'] ?? 'N/A',
                'error' => $e->getMessage(),
            ];
            Log::error("Import error row {$this->rowCount}: " . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nip' => 'required',
            'nama_tanpa_gelar' => 'required',
        ];
    }

    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Handle Excel serial date
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            // Handle string date
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getFailedCount(): int
    {
        return $this->failedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
