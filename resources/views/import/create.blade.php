@extends('layouts.app')

@section('title', 'Import Data Pegawai')

@section('content')
<div class="mb-6">
    <a href="{{ route('import.index') }}" class="text-brand-biru hover:underline">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat Import
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Upload Form -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-brand-biru mb-6">Import File Excel</h2>
        
        <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">File Excel <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <i class="fas fa-file-excel text-4xl text-green-600 mb-3"></i>
                    <p class="text-gray-600 mb-2">Drag & drop file Excel atau</p>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" class="w-full" required>
                </div>
                <p class="text-xs text-gray-500 mt-2">Format: XLSX, XLS, CSV. Maks: 10MB</p>
                @error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
                    <i class="fas fa-upload mr-1"></i> Import
                </button>
                <a href="{{ route('import.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
    
    <!-- Instructions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-brand-biru mb-4">Panduan Import</h3>
        
        <div class="space-y-4 text-sm">
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Format Kolom Excel:</h4>
                <div class="bg-gray-50 p-3 rounded-lg font-mono text-xs overflow-x-auto">
                    <p>NO | NIP | NAMA DENGAN GELAR | NAMA TANPA GELAR | STATUS KEPEGAWAIAN | ...</p>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Kolom Wajib:</h4>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>NIP (unik, tidak boleh duplikat)</li>
                    <li>NAMA TANPA GELAR</li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Catatan:</h4>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Baris pertama harus berisi header kolom</li>
                    <li>Jika NIP sudah ada, data akan di-update</li>
                    <li>Format tanggal: YYYY-MM-DD atau DD/MM/YYYY</li>
                </ul>
            </div>
            
            <div class="pt-4 border-t">
                <a href="{{ route('import.template') }}" class="inline-flex items-center gap-2 text-brand-biru hover:underline">
                    <i class="fas fa-download"></i>
                    Download Template Excel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
