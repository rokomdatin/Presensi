@extends('layouts.app')

@section('title', 'Input Absensi Manual')

@section('content')
<div class="mb-6">
    <a href="{{ route('absensi.index') }}" class="text-brand-biru hover:underline">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Absensi
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <h2 class="text-xl font-bold text-brand-biru mb-6">Input Absensi Manual</h2>
    
    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pegawai <span class="text-red-500">*</span></label>
                <select name="pegawai_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                    <option value="">Pilih Pegawai</option>
                    @foreach($pegawaiList as $p)
                    <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_tanpa_gelar }} ({{ $p->nip }})</option>
                    @endforeach
                </select>
                @error('pegawai_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                    <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="cuti" {{ old('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="dinas_luar" {{ old('status') == 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
                    <option value="tanpa_keterangan" {{ old('status') == 'tanpa_keterangan' ? 'selected' : '' }}>Tanpa Keterangan</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Masuk</label>
                    <input type="time" name="waktu_masuk" value="{{ old('waktu_masuk') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Keluar</label>
                    <input type="time" name="waktu_keluar" value="{{ old('waktu_keluar') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">{{ old('keterangan') }}</textarea>
            </div>
        </div>
        
        <div class="flex gap-3 mt-6">
            <button type="submit" class="px-6 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
            <a href="{{ route('absensi.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
