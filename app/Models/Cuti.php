<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cuti';

    protected $fillable = [
        'pegawai_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'alasan',
        'dokumen_pendukung',
        'status',
        'approved_by',
        'approved_at',
        'catatan_approval',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'approved_at' => 'datetime',
        'jumlah_hari' => 'integer',
    ];

    const JENIS_TAHUNAN = 'tahunan';
    const JENIS_SAKIT = 'sakit';
    const JENIS_MELAHIRKAN = 'melahirkan';
    const JENIS_BESAR = 'besar';
    const JENIS_PENTING = 'penting';
    const JENIS_LUAR_TANGGUNGAN = 'luar_tanggungan';

    const STATUS_PENDING = 'pending';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
