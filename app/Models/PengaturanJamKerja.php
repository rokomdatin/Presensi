<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanJamKerja extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_jam_kerja';

    protected $fillable = [
        'nama_jadwal',
        'jam_masuk',
        'jam_keluar',
        'toleransi_terlambat',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'toleransi_terlambat' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
