@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-brand-biru">Dashboard</h1>
    <p class="text-gray-600">Selamat datang, {{ $user->nama_lengkap }}</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total ASN -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-brand-biru">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total ASN</p>
                <p class="text-3xl font-bold text-brand-biru">{{ $stats['total_asn'] }}</p>
            </div>
            <div class="w-12 h-12 bg-brand-biru/10 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-brand-biru text-xl"></i>
            </div>
        </div>
        <div class="mt-3 flex gap-4 text-xs">
            <span class="text-gray-500">PNS: <strong class="text-brand-biru">{{ $stats['total_pns'] }}</strong></span>
            <span class="text-gray-500">PPPK: <strong class="text-brand-biru">{{ $stats['total_pppk'] }}</strong></span>
        </div>
    </div>
    
    <!-- Hadir Hari Ini -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Hadir Hari Ini</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['hadir_hari_ini'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
        <p class="mt-3 text-xs text-gray-500">Dari total {{ $stats['total_pegawai'] }} pegawai</p>
    </div>
    
    <!-- Terlambat Hari Ini -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Terlambat</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['terlambat_hari_ini'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
        <p class="mt-3 text-xs text-gray-500">Pegawai hari ini</p>
    </div>
    
    <!-- Belum Absen -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-brand-merah">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Belum Absen</p>
                <p class="text-3xl font-bold text-brand-merah">{{ $stats['belum_absen'] }}</p>
            </div>
            <div class="w-12 h-12 bg-brand-merah/10 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-clock text-brand-merah text-xl"></i>
            </div>
        </div>
        <p class="mt-3 text-xs text-gray-500">Pegawai hari ini</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chart Absensi -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-brand-biru mb-4">Rekap Absensi 7 Hari Terakhir</h3>
        <div class="space-y-3">
            @foreach($rekapBulanan as $rekap)
            <div class="flex items-center gap-4">
                <span class="w-16 text-sm text-gray-600">{{ $rekap['tanggal'] }}</span>
                <div class="flex-1 flex gap-1 h-6">
                    @if($rekap['hadir'] > 0)
                    <div class="bg-green-500 rounded" style="width: {{ ($rekap['hadir'] / max($stats['total_pegawai'], 1)) * 100 }}%" title="Hadir: {{ $rekap['hadir'] }}"></div>
                    @endif
                    @if($rekap['tidak_hadir'] > 0)
                    <div class="bg-red-400 rounded" style="width: {{ ($rekap['tidak_hadir'] / max($stats['total_pegawai'], 1)) * 100 }}%" title="Tidak Hadir: {{ $rekap['tidak_hadir'] }}"></div>
                    @endif
                </div>
                <span class="text-sm text-gray-600">{{ $rekap['hadir'] }}/{{ $stats['total_pegawai'] }}</span>
            </div>
            @endforeach
        </div>
        <div class="mt-4 flex gap-4 text-xs">
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-green-500 rounded"></span> Hadir</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-400 rounded"></span> Tidak Hadir</span>
        </div>
    </div>
    
    <!-- Per Unit Kerja -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-brand-biru mb-4">Pegawai per Unit Kerja</h3>
        <div class="space-y-3">
            @foreach($perUnitKerja as $unit)
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600 truncate flex-1 mr-2">{{ $unit->unit_kerja_es_2 ?? 'Lainnya' }}</span>
                <span class="font-semibold text-brand-biru">{{ $unit->total }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Jenis Kelamin -->
<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-semibold text-brand-biru mb-4">Statistik Jenis Kelamin</h3>
    <div class="flex gap-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-male text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Laki-laki</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['total_laki'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-female text-pink-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Perempuan</p>
                <p class="text-2xl font-bold text-pink-600">{{ $stats['total_perempuan'] }}</p>
            </div>
        </div>
    </div>
</div>

@if($user->role === 'admin')
<!-- Admin Quick Actions -->
<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-semibold text-brand-biru mb-4">Quick Actions (Admin)</h3>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-biru text-white rounded-lg hover:bg-brand-biru/90 transition">
            <i class="fas fa-user-cog"></i>
            Kelola User
        </a>
        <a href="{{ route('pegawai.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
            <i class="fas fa-user-plus"></i>
            Tambah Pegawai
        </a>
        <a href="{{ route('import.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-file-import"></i>
            Import Excel
        </a>
    </div>
</div>
@endif

@endsection
