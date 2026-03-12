@extends('layouts.app')

@section('title', 'Import Data')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-brand-biru">Import Data</h1>
        <p class="text-gray-600">Import data pegawai dari file Excel</p>
    </div>
    <a href="{{ route('import.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
        <i class="fas fa-upload"></i>
        Import Baru
    </a>
</div>

<!-- Import History -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-4 border-b">
        <h3 class="font-semibold text-brand-biru">Riwayat Import</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">File</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">User</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Total</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Berhasil</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Gagal</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($importLogs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">{{ $log->nama_file }}</td>
                    <td class="px-4 py-3">{{ $log->user->nama_lengkap ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $log->total_rows }}</td>
                    <td class="px-4 py-3 text-green-600">{{ $log->success_rows }}</td>
                    <td class="px-4 py-3 text-red-600">{{ $log->failed_rows }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColor = match($log->status) {
                                'completed' => 'green',
                                'processing' => 'yellow',
                                'failed' => 'red',
                                default => 'gray'
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 capitalize">
                            {{ $log->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        Belum ada riwayat import
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($importLogs->hasPages())
    <div class="px-4 py-3 border-t">
        {{ $importLogs->links() }}
    </div>
    @endif
</div>
@endsection
