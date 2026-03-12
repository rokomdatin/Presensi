@extends('layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="mb-6">
    <a href="{{ route('cuti.index') }}" class="text-brand-biru hover:underline">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Cuti
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <h2 class="text-xl font-bold text-brand-biru mb-6">Ajukan Cuti</h2>
    
    <form action="{{ route('cuti.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-4">
            @if(isset($pegawaiList))
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pegawai <span class="text-red-500">*</span></label>
                <select name="pegawai_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                    <option value="">Pilih Pegawai</option>
                    @foreach($pegawaiList as $p)
                    <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_tanpa_gelar }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600">Pegawai: <strong>{{ $pegawai->nama_tanpa_gelar ?? '-' }}</strong></p>
                @if(isset($saldoCuti))
                <p class="text-sm text-gray-600 mt-1">Saldo Cuti Tahunan: <strong>{{ $saldoCuti->saldo_sisa ?? 12 }} hari</strong></p>
                @endif
            </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti <span class="text-red-500">*</span></label>
                <select name="jenis_cuti" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                    <option value="">Pilih Jenis Cuti</option>
                    <option value="tahunan" {{ old('jenis_cuti') == 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="sakit" {{ old('jenis_cuti') == 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                    <option value="melahirkan" {{ old('jenis_cuti') == 'melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                    <option value="besar" {{ old('jenis_cuti') == 'besar' ? 'selected' : '' }}>Cuti Besar</option>
                    <option value="penting" {{ old('jenis_cuti') == 'penting' ? 'selected' : '' }}>Cuti Alasan Penting</option>
                    <option value="luar_tanggungan" {{ old('jenis_cuti') == 'luar_tanggungan' ? 'selected' : '' }}>Cuti di Luar Tanggungan</option>
                </select>
                @error('jenis_cuti')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                    @error('tanggal_mulai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                    @error('tanggal_selesai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan <span class="text-red-500">*</span></label>
                <textarea name="alasan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>{{ old('alasan') }}</textarea>
                @error('alasan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dokumen Pendukung</label>
                <input type="file" name="dokumen_pendukung" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Maks: 5MB (PDF, JPG, PNG)</p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 mt-6">
            <button type="submit" class="px-6 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
                <i class="fas fa-paper-plane mr-1"></i> Ajukan
            </button>
            <a href="{{ route('cuti.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
