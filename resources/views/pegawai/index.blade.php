@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-brand-biru">Data Pegawai</h1>
        <p class="text-gray-600">
            @if($user->role === 'keuangan')
                Data keuangan pegawai
            @else
                Daftar seluruh pegawai
            @endif
        </p>
    </div>
    @if(in_array($user->role, ['admin', 'kepegawaian']))
    <a href="{{ route('pegawai.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
        <i class="fas fa-plus"></i>
        Tambah Pegawai
    </a>
    @endif
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form action="{{ route('pegawai.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <!-- Search -->
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Cari Data</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent"
                   placeholder="Nama, NIP, atau jabatan...">
        </div>
        
        @if($user->role !== 'keuangan')
        <!-- Filter Status -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status_kepegawaian" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent min-w-[150px]">
                <option value="">Semua Status</option>
                @foreach($statusList as $status)
                <option value="{{ $status }}" {{ request('status_kepegawaian') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Filter Unit Kerja -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Unit Kerja</label>
            <select name="unit_kerja" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent min-w-[180px]">
                <option value="">Semua Unit</option>
                @foreach($unitKerjaList as $unit)
                <option value="{{ $unit }}" {{ request('unit_kerja') == $unit ? 'selected' : '' }}>{{ Str::limit($unit, 25) }}</option>
                @endforeach
            </select>
        </div>
        @endif
        
        <!-- Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand-biru text-white rounded-lg hover:bg-brand-biru/90 transition" title="Cari">
                <i class="fas fa-search"></i>
            </button>
            @if(request()->hasAny(['search', 'status_kepegawaian', 'unit_kerja']))
            <a href="{{ route('pegawai.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition" title="Reset">
                <i class="fas fa-times"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Result Info -->
<div class="flex items-center justify-between mb-3 text-sm text-gray-600">
    <p>Menampilkan {{ $pegawai->firstItem() ?? 0 }} - {{ $pegawai->lastItem() ?? 0 }} dari {{ $pegawai->total() }} data</p>
    @if(request()->hasAny(['search', 'status_kepegawaian', 'unit_kerja']))
    <span class="px-2 py-1 bg-gray-100 rounded text-xs">
        <i class="fas fa-filter mr-1"></i>Filter aktif
    </span>
    @endif
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-brand-biru text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold w-12">No</th>
                    <th class="px-4 py-3 text-left font-semibold">NIP</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold">Jabatan</th>
                    
                    @if($user->role === 'keuangan')
                        {{-- Kolom khusus Keuangan --}}
                        <th class="px-4 py-3 text-left font-semibold">Unit Kerja</th>
                        <th class="px-4 py-3 text-left font-semibold">NPWP</th>
                        <th class="px-4 py-3 text-left font-semibold">Rek. BNI</th>
                        <th class="px-4 py-3 text-left font-semibold">Rek. BRI</th>
                        <th class="px-4 py-3 text-left font-semibold">KGB</th>
                        <th class="px-4 py-3 text-left font-semibold">Operator</th>
                    @else
                        {{-- Kolom untuk Admin/Kepegawaian/Guest --}}
                        <th class="px-4 py-3 text-left font-semibold">Unit Kerja</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Pangkat</th>
                        @if($user->role === 'admin')
                            <th class="px-4 py-3 text-left font-semibold">NPWP</th>
                        @endif
                    @endif
                    
                    <th class="px-4 py-3 text-center font-semibold w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pegawai as $index => $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">{{ $pegawai->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->nip }}</code>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($p->foto)
                                <img src="{{ Storage::url($p->foto) }}" alt="{{ $p->nama_tanpa_gelar }}" 
                                     class="w-10 h-10 rounded-full object-cover border">
                            @else
                                <div class="w-10 h-10 bg-brand-biru rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">
                                        {{ strtoupper(substr($p->nama_tanpa_gelar, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $p->nama_tanpa_gelar }}</p>
                                @if($p->nama_dengan_gelar !== $p->nama_tanpa_gelar)
                                    <p class="text-xs text-gray-500 truncate">{{ $p->nama_dengan_gelar }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-gray-700">{{ $p->jabatan ?? '-' }}</span>
                    </td>
                    
                    @if($user->role === 'keuangan')
                        {{-- Data khusus Keuangan --}}
                        <td class="px-4 py-3 text-gray-600">{{ Str::limit($p->unit_kerja_es_2, 20) ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->npwp ?? '-' }}</code>
                        </td>
                        <td class="px-4 py-3">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->no_rek_bni ?? '-' }}</code>
                        </td>
                        <td class="px-4 py-3">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->no_rek_bri ?? '-' }}</code>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            @if($p->kgb_tahun || $p->kgb_bulan)
                                <span class="text-xs">{{ $p->kgb_tahun ?? 0 }} Th {{ $p->kgb_bulan ?? 0 }} Bl</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->operator_keuangan ?? '-' }}</td>
                    @else
                        {{-- Data untuk Admin/Kepegawaian/Guest --}}
                        <td class="px-4 py-3 text-gray-600">{{ Str::limit($p->unit_kerja_es_2, 20) ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'PNS' => 'bg-blue-100 text-blue-700',
                                    'PPPK' => 'bg-green-100 text-green-700',
                                    'PPNPN' => 'bg-yellow-100 text-yellow-700',
                                    'Kontrak' => 'bg-purple-100 text-purple-700',
                                    'Honorer' => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$p->status_kepegawaian] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $p->status_kepegawaian ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->pangkat ?? '-' }}</td>
                        @if($user->role === 'admin')
                            <td class="px-4 py-3">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->npwp ?? '-' }}</code>
                            </td>
                        @endif
                    @endif
                    
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('pegawai.show', $p->id) }}" 
                               class="p-2 text-brand-biru hover:bg-brand-biru/10 rounded-lg transition" 
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(in_array($user->role, ['admin', 'kepegawaian']))
                            <a href="{{ route('pegawai.edit', $p->id) }}" 
                               class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if($user->role === 'admin')
                            <form action="{{ route('pegawai.destroy', $p->id) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Yakin ingin menghapus data pegawai ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $user->role === 'keuangan' ? 11 : ($user->role === 'admin' ? 10 : 9) }}" 
                        class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-3"></i>
                            <p class="text-lg font-medium text-gray-500">Tidak ada data pegawai</p>
                            <p class="text-sm mt-1">
                                @if(request()->hasAny(['search', 'status_kepegawaian', 'unit_kerja']))
                                    Coba ubah filter pencarian Anda
                                @elseif(in_array($user->role, ['admin', 'kepegawaian']))
                                    <a href="{{ route('pegawai.create') }}" class="text-brand-biru hover:underline">
                                        Tambah pegawai pertama
                                    </a>
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($pegawai->hasPages())
    <div class="px-4 py-3 border-t bg-gray-50">
        {{ $pegawai->links() }}
    </div>
    @endif
</div>

<!-- Info Box per Role -->
@if($user->role === 'keuangan')
<div class="mt-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm flex items-start gap-2">
    <i class="fas fa-info-circle mt-0.5"></i>
    <div>
        <strong>Mode Keuangan:</strong> Anda hanya dapat melihat data keuangan pegawai (NPWP, Rekening, KGB, Operator). 
        Untuk mengubah data, hubungi admin atau bagian kepegawaian.
    </div>
</div>
@endif

@if($user->isGuest())
<div class="mt-4 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg text-sm flex items-start gap-2">
    <i class="fas fa-lock mt-0.5"></i>
    <div>
        <strong>Akses Terbatas:</strong> Anda hanya dapat melihat data Anda sendiri. 
        Hubungi admin untuk permintaan perubahan data.
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Auto-submit filter saat change (opsional, uncomment jika diinginkan)
/*
document.querySelectorAll('select[name="status_kepegawaian"], select[name="unit_kerja"]').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
*/

// Konfirmasi delete dengan SweetAlert (jika library tersedia)
document.querySelectorAll('form[method="POST"][onsubmit]').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (typeof Swal !== 'undefined') {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus data?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        }
    });
});
</script>
@endpush@extends('layouts.app')

@section('title', 'Data Pegawai')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-brand-biru">Data Pegawai</h1>
        <p class="text-gray-600">
            @if($user->role === 'keuangan')
                Data keuangan pegawai
            @else
                Daftar seluruh pegawai
            @endif
        </p>
    </div>
    @if(in_array($user->role, ['admin', 'kepegawaian']))
    <a href="{{ route('pegawai.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
        <i class="fas fa-plus"></i>
        Tambah Pegawai
    </a>
    @endif
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form action="{{ route('pegawai.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <!-- Search -->
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Cari Data</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent"
                   placeholder="Nama, NIP, atau jabatan...">
        </div>
        
        @if($user->role !== 'keuangan')
        <!-- Filter Status -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status_kepegawaian" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent min-w-[150px]">
                <option value="">Semua Status</option>
                @foreach($statusList as $status)
                <option value="{{ $status }}" {{ request('status_kepegawaian') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Filter Unit Kerja -->
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Unit Kerja</label>
            <select name="unit_kerja" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent min-w-[180px]">
                <option value="">Semua Unit</option>
                @foreach($unitKerjaList as $unit)
                <option value="{{ $unit }}" {{ request('unit_kerja') == $unit ? 'selected' : '' }}>{{ Str::limit($unit, 25) }}</option>
                @endforeach
            </select>
        </div>
        @endif
        
        <!-- Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand-biru text-white rounded-lg hover:bg-brand-biru/90 transition" title="Cari">
                <i class="fas fa-search"></i>
            </button>
            @if(request()->hasAny(['search', 'status_kepegawaian', 'unit_kerja']))
            <a href="{{ route('pegawai.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition" title="Reset">
                <i class="fas fa-times"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Result Info -->
<div class="flex items-center justify-between mb-3 text-sm text-gray-600">
    <p>Menampilkan {{ $pegawai->firstItem() ?? 0 }} - {{ $pegawai->lastItem() ?? 0 }} dari {{ $pegawai->total() }} data</p>
    @if(request()->hasAny(['search', 'status_kepegawaian', 'unit_kerja']))
    <span class="px-2 py-1 bg-gray-100 rounded text-xs">
        <i class="fas fa-filter mr-1"></i>Filter aktif
    </span>
    @endif
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-brand-biru text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold w-12">No</th>
                    <th class="px-4 py-3 text-left font-semibold">NIP</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold">Jabatan</th>
                    
                    @if($user->role === 'keuangan')
                        {{-- Kolom khusus Keuangan --}}
                        <th class="px-4 py-3 text-left font-semibold">Unit Kerja</th>
                        <th class="px-4 py-3 text-left font-semibold">NPWP</th>
                        <th class="px-4 py-3 text-left font-semibold">Rek. BNI</th>
                        <th class="px-4 py-3 text-left font-semibold">Rek. BRI</th>
                        <th class="px-4 py-3 text-left font-semibold">KGB</th>
                        <th class="px-4 py-3 text-left font-semibold">Operator</th>
                    @else
                        {{-- Kolom untuk Admin/Kepegawaian/Guest --}}
                        <th class="px-4 py-3 text-left font-semibold">Unit Kerja</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Pangkat</th>
                        @if($user->role === 'admin')
                            <th class="px-4 py-3 text-left font-semibold">NPWP</th>
                        @endif
                    @endif
                    
                    <th class="px-4 py-3 text-center font-semibold w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pegawai as $index => $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">{{ $pegawai->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->nip }}</code>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($p->foto)
                                <img src="{{ Storage::url($p->foto) }}" alt="{{ $p->nama_tanpa_gelar }}" 
                                     class="w-10 h-10 rounded-full object-cover border">
                            @else
                                <div class="w-10 h-10 bg-brand-biru rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">
                                        {{ strtoupper(substr($p->nama_tanpa_gelar, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $p->nama_tanpa_gelar }}</p>
                                @if($p->nama_dengan_gelar !== $p->nama_tanpa_gelar)
                                    <p class="text-xs text-gray-500 truncate">{{ $p->nama_dengan_gelar }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-gray-700">{{ $p->jabatan ?? '-' }}</span>
                    </td>
                    
                    @if($user->role === 'keuangan')
                        {{-- Data khusus Keuangan --}}
                        <td class="px-4 py-3 text-gray-600">{{ Str::limit($p->unit_kerja_es_2, 20) ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->npwp ?? '-' }}</code>
                        </td>
                        <td class="px-4 py-3">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->no_rek_bni ?? '-' }}</code>
                        </td>
                        <td class="px-4 py-3">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->no_rek_bri ?? '-' }}</code>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            @if($p->kgb_tahun || $p->kgb_bulan)
                                <span class="text-xs">{{ $p->kgb_tahun ?? 0 }} Th {{ $p->kgb_bulan ?? 0 }} Bl</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->operator_keuangan ?? '-' }}</td>
                    @else
                        {{-- Data untuk Admin/Kepegawaian/Guest --}}
                        <td class="px-4 py-3 text-gray-600">{{ Str::limit($p->unit_kerja_es_2, 20) ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'PNS' => 'bg-blue-100 text-blue-700',
                                    'PPPK' => 'bg-green-100 text-green-700',
                                    'PPNPN' => 'bg-yellow-100 text-yellow-700',
                                    'Kontrak' => 'bg-purple-100 text-purple-700',
                                    'Honorer' => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$p->status_kepegawaian] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $p->status_kepegawaian ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->pangkat ?? '-' }}</td>
                        @if($user->role === 'admin')
                            <td class="px-4 py-3">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $p->npwp ?? '-' }}</code>
                            </td>
                        @endif
                    @endif
                    
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('pegawai.show', $p->id) }}" 
                               class="p-2 text-brand-biru hover:bg-brand-biru/10 rounded-lg transition" 
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(in_array($user->role, ['admin', 'kepegawaian']))
                            <a href="{{ route('pegawai.edit', $p->id) }}" 
                               class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if($user->role === 'admin')
                            <form action="{{ route('pegawai.destroy', $p->id) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Yakin ingin menghapus data pegawai ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $user->role === 'keuangan' ? 11 : ($user->role === 'admin' ? 10 : 9) }}" 
                        class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-3"></i>
                            <p class="text-lg font-medium text-gray-500">Tidak ada data pegawai</p>
                            <p class="text-sm mt-1">
                                @if(request()->hasAny(['search', 'status_kepegawaian', 'unit_kerja']))
                                    Coba ubah filter pencarian Anda
                                @elseif(in_array($user->role, ['admin', 'kepegawaian']))
                                    <a href="{{ route('pegawai.create') }}" class="text-brand-biru hover:underline">
                                        Tambah pegawai pertama
                                    </a>
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($pegawai->hasPages())
    <div class="px-4 py-3 border-t bg-gray-50">
        {{ $pegawai->links() }}
    </div>
    @endif
</div>

<!-- Info Box per Role -->
@if($user->role === 'keuangan')
<div class="mt-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm flex items-start gap-2">
    <i class="fas fa-info-circle mt-0.5"></i>
    <div>
        <strong>Mode Keuangan:</strong> Anda hanya dapat melihat data keuangan pegawai (NPWP, Rekening, KGB, Operator). 
        Untuk mengubah data, hubungi admin atau bagian kepegawaian.
    </div>
</div>
@endif

@if($user->isGuest())
<div class="mt-4 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg text-sm flex items-start gap-2">
    <i class="fas fa-lock mt-0.5"></i>
    <div>
        <strong>Akses Terbatas:</strong> Anda hanya dapat melihat data Anda sendiri. 
        Hubungi admin untuk permintaan perubahan data.
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Auto-submit filter saat change (opsional, uncomment jika diinginkan)
/*
document.querySelectorAll('select[name="status_kepegawaian"], select[name="unit_kerja"]').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
*/

// Konfirmasi delete dengan SweetAlert (jika library tersedia)
document.querySelectorAll('form[method="POST"][onsubmit]').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (typeof Swal !== 'undefined') {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus data?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        }
    });
});
</script>
@endpush