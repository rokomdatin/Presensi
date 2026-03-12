<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'nama_lengkap',
        'role',
        'nip',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_KEPEGAWAIAN = 'kepegawaian';
    const ROLE_KEUANGAN = 'keuangan';
    const ROLE_GUEST = 'guest';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isKepegawaian(): bool
    {
        return $this->role === self::ROLE_KEPEGAWAIAN;
    }

    public function isKeuangan(): bool
    {
        return $this->role === self::ROLE_KEUANGAN;
    }

    public function isGuest(): bool
    {
        return $this->role === self::ROLE_GUEST;
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'nip', 'nip');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }
}
