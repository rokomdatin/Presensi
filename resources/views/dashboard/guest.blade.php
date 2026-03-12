@extends('layouts.app')

@section('title', 'Dashboard - Data Pribadi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-brand-biru">Data Pribadi</h1>
    <p class="text-gray-600">Selamat datang, {{ $user->nama_lengkap }}</p>
</div>

@if($pegawaiPribadi)
<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex flex-wrap gap-4">
        <form action="{{ route('absensi.clock-in') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-sign-in-alt"></i>
                Clock In
            </button>
        </form>
        <form action="{{ route('absensi.clock-out') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
                <i class="fas fa-sign-out-alt"></i>
                Clock Out
            </button>
        </form>
        <a href="{{ route('cuti.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-biru text-white rounded-lg hover:bg-brand-biru/90 transition">
            <i class="fas fa-calendar-plus"></i>
            Ajukan Cuti
        </a>
    </div>
</div>

<!-- Data Pribadi -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 bg-brand-biru rounded-full flex items-center justify-center">
                <span class="text-white text-2xl font-bold">{{ substr($pegawaiPribadi->nama_tanpa_gelar, 0, 1) }}</span>
            </div>
            <div>
                <h3 class="font-semibold text-brand-biru">{{ $pegawaiPribadi->nama_dengan_gelar }}</h3>
                <p class="text-gray-500 text-sm">NIP: {{ $pegawaiPribadi->nip }}</p>
            </div>
        </div>
        <div class="space-y-2 text-sm">
            <p><span class="text-gray-500">Jabatan:</span> <span class="font-medium">{{ $pegawaiPribadi->jabatan ?? '-' }}</span></p>
            <p><span class="text-gray-500">Unit Kerja:</span> <span class="font-medium">{{ $pegawaiPribadi->unit_kerja_es_2 ?? '-' }}</span></p>
            <p><span class="text-gray-500">Pangkat:</span> <span class="font-medium">{{ $pegawaiPribadi->pangkat ?? '-' }}</span></p>
        </div>
    </div>
    
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-brand-biru mb-4">Informasi Pribadi</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Tempat, Tanggal Lahir</p>
                <p class="font-medium">{{ $pegawaiPribadi->tempat_lahir ?? '-' }}, {{ $pegawaiPribadi->tanggal_lahir?->format('d M Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Jenis Kelamin</p>
                <p class="font-medium">{{ $pegawaiPribadi->jenis_kelamin ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Pendidikan Terakhir</p>
                <p class="font-medium">{{ $pegawaiPribadi->pendidikan_terakhir ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Nomor HP</p>
                <p class="font-medium">{{ $pegawaiPribadi->nomor_hp ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Absensi -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-semibold text-brand-biru mb-4">Riwayat Absensi Terakhir</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Masuk</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Keluar</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($absensiPribadi as $absensi)
                <tr>
                    <td class="px-4 py-3">{{ $absensi->tanggal->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ $absensi->waktu_masuk ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $absensi->waktu_keluar ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColor = match($absensi->status) {
                                'hadir' => 'green',
                                'izin', 'sakit', 'cuti' => 'yellow',
                                'tanpa_keterangan' => 'red',
                                default => 'gray'
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 capitalize">
                            {{ str_replace('_', ' ', $absensi->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($absensi->terlambat)
                            <span class="text-yellow-600">Terlambat {{ $absensi->menit_terlambat }} menit</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data absensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-6 py-4 rounded-lg">
    <p><i class="fas fa-exclamation-triangle mr-2"></i> Data pegawai Anda belum terdaftar. Silakan hubungi administrator.</p>
</div>
@endif
@endsection
