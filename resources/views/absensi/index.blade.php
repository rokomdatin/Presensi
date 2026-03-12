@extends('layouts.app')

@section('title', 'Absensi')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-brand-biru">Data Absensi</h1>
        <p class="text-gray-600">Kelola absensi pegawai</p>
    </div>
    <div class="flex flex-wrap gap-3">
        @if(!$user->isGuest())
        <form action="{{ route('absensi.clock-in') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-sign-in-alt"></i>
                Clock In
            </button>
        </form>
        <form action="{{ route('absensi.clock-out') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
                <i class="fas fa-sign-out-alt"></i>
                Clock Out
            </button>
        </form>
        @endif
        @if(in_array($user->role, ['admin', 'kepegawaian']))
        <a href="{{ route('absensi.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-biru text-white rounded-lg hover:bg-brand-biru/90 transition">
            <i class="fas fa-plus"></i>
            Input Manual
        </a>
        @endif
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form action="{{ route('absensi.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                <option value="">Semua</option>
                @foreach(['hadir', 'izin', 'sakit', 'cuti', 'dinas_luar', 'tanpa_keterangan'] as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
        @if(!$user->isGuest() && count($pegawaiList) > 0)
        <div>
            <label class="block text-xs text-gray-500 mb-1">Pegawai</label>
            <select name="pegawai_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                <option value="">Semua Pegawai</option>
                @foreach($pegawaiList as $p)
                <option value="{{ $p->id }}" {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_tanpa_gelar }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="flex items-end">
            <button type="submit" class="px-4 py-2 bg-brand-biru text-white rounded-lg hover:bg-brand-biru/90 transition">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-brand-biru text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    @if(!$user->isGuest())
                    <th class="px-4 py-3 text-left font-semibold">Pegawai</th>
                    @endif
                    <th class="px-4 py-3 text-left font-semibold">Masuk</th>
                    <th class="px-4 py-3 text-left font-semibold">Keluar</th>
                    <th class="px-4 py-3 text-left font-semibold">Durasi</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($absensi as $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $a->tanggal->format('d M Y') }}</td>
                    @if(!$user->isGuest())
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-brand-biru rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ substr($a->pegawai->nama_tanpa_gelar ?? '-', 0, 1) }}</span>
                            </div>
                            <span>{{ $a->pegawai->nama_tanpa_gelar ?? '-' }}</span>
                        </div>
                    </td>
                    @endif
                    <td class="px-4 py-3">
                        @if($a->waktu_masuk)
                        <span class="{{ $a->terlambat ? 'text-red-600' : 'text-green-600' }}">
                            {{ $a->waktu_masuk }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $a->waktu_keluar ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $a->durasi_kerja_format }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColor = match($a->status) {
                                'hadir' => 'green',
                                'izin', 'sakit', 'cuti', 'dinas_luar' => 'yellow',
                                'tanpa_keterangan' => 'red',
                                default => 'gray'
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 capitalize">
                            {{ str_replace('_', ' ', $a->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($a->terlambat)
                        <span class="text-red-600">Terlambat {{ $a->menit_terlambat }} menit</span>
                        @else
                        {{ $a->keterangan ?? '-' }}
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $user->isGuest() ? 6 : 7 }}" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-2"></i>
                        <p>Tidak ada data absensi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($absensi->hasPages())
    <div class="px-4 py-3 border-t">
        {{ $absensi->links() }}
    </div>
    @endif
</div>
@endsection
