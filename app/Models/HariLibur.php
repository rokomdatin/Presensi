<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    use HasFactory;

    protected $table = 'hari_libur';

    protected $fillable = [
        'tanggal',
        'nama_libur',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public static function isLibur($tanggal): bool
    {
        return self::whereDate('tanggal', $tanggal)->exists();
    }
}
