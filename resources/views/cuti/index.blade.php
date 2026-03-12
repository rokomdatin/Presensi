@extends('layouts.app')

@section('title', 'Cuti')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-brand-biru">Data Cuti</h1>
        <p class="text-gray-600">Kelola pengajuan cuti</p>
    </div>
    <a href="{{ route('cuti.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
        <i class="fas fa-plus"></i>
        Ajukan Cuti
    </a>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-brand-biru text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Pegawai</th>
                    <th class="px-4 py-3 text-left font-semibold">Jenis Cuti</th>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Jumlah Hari</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Disetujui Oleh</th>
                    @if(in_array($user->role, ['admin', 'kepegawaian']))
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($cutiList as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $c->pegawai->nama_tanpa_gelar ?? '-' }}</td>
                    <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $c->jenis_cuti) }}</td>
                    <td class="px-4 py-3">{{ $c->tanggal_mulai->format('d M Y') }} - {{ $c->tanggal_selesai->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ $c->jumlah_hari }} hari</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColor = match($c->status) {
                                'pending' => 'yellow',
                                'disetujui' => 'green',
                                'ditolak' => 'red',
                                default => 'gray'
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 capitalize">
                            {{ $c->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $c->approvedBy->nama_lengkap ?? '-' }}</td>
                    @if(in_array($user->role, ['admin', 'kepegawaian']))
                    <td class="px-4 py-3">
                        @if($c->status === 'pending')
                        <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('cuti.approve', $c->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="disetujui">
                                <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('cuti.approve', $c->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ in_array($user->role, ['admin', 'kepegawaian']) ? 7 : 6 }}" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data cuti
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($cutiList->hasPages())
    <div class="px-4 py-3 border-t">
        {{ $cutiList->links() }}
    </div>
    @endif
</div>
@endsection
