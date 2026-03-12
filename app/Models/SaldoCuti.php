<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoCuti extends Model
{
    use HasFactory;

    protected $table = 'saldo_cuti';

    protected $fillable = [
        'pegawai_id',
        'tahun',
        'saldo_awal',
        'saldo_terpakai',
        'saldo_sisa',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'saldo_awal' => 'integer',
        'saldo_terpakai' => 'integer',
        'saldo_sisa' => 'integer',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function kurangiSaldo(int $jumlah)
    {
        $this->saldo_terpakai += $jumlah;
        $this->saldo_sisa = $this->saldo_awal - $this->saldo_terpakai;
        $this->save();
    }
}
