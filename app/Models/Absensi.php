<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'waktu_masuk',
        'lokasi_masuk',
        'lat_masuk',
        'lng_masuk',
        'foto_masuk',
        'metode_masuk',
        'waktu_keluar',
        'lokasi_keluar',
        'lat_keluar',
        'lng_keluar',
        'foto_keluar',
        'metode_keluar',
        'status',
        'keterangan',
        'durasi_kerja',
        'terlambat',
        'menit_terlambat',
        'pulang_cepat',
        'menit_pulang_cepat',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'terlambat' => 'boolean',
        'pulang_cepat' => 'boolean',
        'durasi_kerja' => 'integer',
        'menit_terlambat' => 'integer',
        'menit_pulang_cepat' => 'integer',
    ];

    const STATUS_HADIR = 'hadir';
    const STATUS_IZIN = 'izin';
    const STATUS_SAKIT = 'sakit';
    const STATUS_CUTI = 'cuti';
    const STATUS_DINAS_LUAR = 'dinas_luar';
    const STATUS_TANPA_KETERANGAN = 'tanpa_keterangan';
    const STATUS_LIBUR = 'libur';

    const METODE_FINGERPRINT = 'fingerprint';
    const METODE_MANUAL = 'manual';
    const METODE_MOBILE = 'mobile';
    const METODE_WEB = 'web';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hitungDurasiKerja()
    {
        if ($this->waktu_masuk && $this->waktu_keluar) {
            $masuk = Carbon::parse($this->waktu_masuk);
            $keluar = Carbon::parse($this->waktu_keluar);
            $this->durasi_kerja = $masuk->diffInMinutes($keluar);
            $this->save();
        }
    }

    public function cekKeterlambatan()
    {
        $setting = PengaturanJamKerja::where('is_active', true)->first();
        
        if ($setting && $this->waktu_masuk) {
            $jamMasukSetting = Carbon::parse($setting->jam_masuk);
            $toleransi = $setting->toleransi_terlambat;
            $waktuMasuk = Carbon::parse($this->waktu_masuk);
            
            $batasTerlambat = $jamMasukSetting->copy()->addMinutes($toleransi);
            
            if ($waktuMasuk->gt($batasTerlambat)) {
                $this->terlambat = true;
                $this->menit_terlambat = $jamMasukSetting->diffInMinutes($waktuMasuk);
            } else {
                $this->terlambat = false;
                $this->menit_terlambat = 0;
            }
            $this->save();
        }
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', Carbon::today());
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', Carbon::now()->month)
                     ->whereYear('tanggal', Carbon::now()->year);
    }

    public function getDurasiKerjaFormatAttribute()
    {
        if ($this->durasi_kerja) {
            $jam = floor($this->durasi_kerja / 60);
            $menit = $this->durasi_kerja % 60;
            return "{$jam} jam {$menit} menit";
        }
        return '-';
    }
}
