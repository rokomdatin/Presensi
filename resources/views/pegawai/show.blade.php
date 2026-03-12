@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')
<div class="mb-6">
    <a href="{{ route('pegawai.index') }}" class="inline-flex items-center text-brand-biru hover:text-brand-biru/80 transition">
        <i class="fas fa-arrow-left mr-2"></i>
        <span>Kembali ke Daftar Pegawai</span>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <!-- Header Profile -->
    <div class="bg-gradient-to-r from-brand-biru to-brand-biru/90 p-6 text-white">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-4">
            <!-- Foto / Avatar -->
            <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center border-4 border-white/30">
                @if($pegawai->foto)
                    <img src="{{ Storage::url($pegawai->foto) }}" 
                         alt="{{ $pegawai->nama_tanpa_gelar }}" 
                         class="w-full h-full rounded-full object-cover">
                @else
                    <span class="text-white text-3xl font-bold">
                        {{ strtoupper(substr($pegawai->nama_tanpa_gelar, 0, 1)) }}
                    </span>
                @endif
            </div>
            
            <!-- Info Utama -->
            <div class="text-center md:text-left flex-1">
                <h1 class="text-2xl font-bold">{{ $pegawai->nama_dengan_gelar }}</h1>
                <p class="text-white/90 font-mono text-sm mt-1">
                    <i class="fas fa-id-card mr-1"></i> NIP: {{ $pegawai->nip }}
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-3">
                    <span class="inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-sm">
                        {{ $pegawai->status_kepegawaian ?? '-' }}
                    </span>
                    @if($pegawai->jenis_kepegawaian)
                    <span class="inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-sm">
                        {{ $pegawai->jenis_kepegawaian }}
                    </span>
                    @endif
                    @if($pegawai->pangkat)
                    <span class="inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-sm">
                        {{ $pegawai->pangkat }}
                    </span>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            @if(in_array($user->role, ['admin', 'kepegawaian']))
            <div class="flex gap-2">
                <a href="{{ route('pegawai.edit', $pegawai->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white text-brand-biru rounded-lg hover:bg-white/90 transition font-medium">
                    <i class="fas fa-edit"></i>
                    Edit
                </a>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Content -->
    <div class="p-6">
    
        @if($user->role === 'keuangan')
        {{-- VIEW KHUSUS KEUANGAN --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-wallet"></i> Data Keuangan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Nama Lengkap</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->nama_dengan_gelar }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">NIP</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->nip }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Jabatan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->jabatan ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Unit Kerja</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->unit_kerja_es_2 ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-brand-biru">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">NPWP</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->npwp ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">No. Rekening BNI</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->no_rek_bni ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">No. Rekening BRI</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->no_rek_bri ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">KGB</p>
                    <p class="font-medium text-gray-900 mt-1">
                        @if($pegawai->kgb_tahun || $pegawai->kgb_bulan)
                            {{ $pegawai->kgb_tahun ?? 0 }} Tahun {{ $pegawai->kgb_bulan ?? 0 }} Bulan
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Operator Keuangan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->operator_keuangan ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        @else
        {{-- VIEW UNTUK ADMIN/KEPEGAWAIAN/GUEST --}}
        
        <!-- Data Identitas Dasar -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-user"></i> Data Identitas Dasar
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">No Urut</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->no ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Kode Fingerprint</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->kode_fingerprint ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Status Kepegawaian</p>
                    <p class="font-medium text-gray-900 mt-1">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                            {{ $pegawai->status_kepegawaian === 'PNS' ? 'bg-blue-100 text-blue-700' : 
                               ($pegawai->status_kepegawaian === 'PPPK' ? 'bg-green-100 text-green-700' : 
                               'bg-gray-100 text-gray-700') }}">
                            {{ $pegawai->status_kepegawaian ?? '-' }}
                        </span>
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Jenis Kepegawaian</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->jenis_kepegawaian ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Nama dengan Gelar</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->nama_dengan_gelar }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Nama tanpa Gelar</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->nama_tanpa_gelar }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">NIP</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->nip }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Tempat Lahir</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tempat_lahir ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Tanggal Lahir</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tanggal_lahir?->format('d F Y') ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Jenis Kelamin</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->jenis_kelamin ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Status Perkawinan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->status_perkawinan ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Jumlah Anak</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->jumlah_anak ?? 0 }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Agama</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->agama ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">NIK</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->nik ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Nomor HP</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->nomor_hp ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Data Jabatan -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-briefcase"></i> Data Jabatan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Jabatan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->jabatan ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Eselon</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->eselon ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Kelas Jabatan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->kelas_jabatan ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Tanggal SK</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tanggal_sk?->format('d F Y') ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">TMT Jabatan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tmt_jabatan?->format('d F Y') ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Nomor SK Jabatan</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->nomor_sk_jabatan ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Angka Kredit SK</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->angka_kredit_sk ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Angka Kredit Terakhir</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->angka_kredit_jabatan_fungsional_terakhir ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg lg:col-span-3">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Riwayat Jabatan Fungsional</p>
                    <p class="font-medium text-gray-900 mt-1 whitespace-pre-wrap">{{ $pegawai->riwayat_jabatan_fungsional ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Data Unit Kerja -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-building"></i> Data Unit Kerja
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Unit Kerja Eselon 1</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->unit_kerja_eselon_1 ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Unit Kerja Eselon 2</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->unit_kerja_es_2 ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Unit Kerja Eselon 3</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->unit_kerja_es_3 ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Unit Kerja Eselon 4</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->unit_kerja_es_4 ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Data Pangkat -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-medal"></i> Data Pangkat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Pangkat/Golongan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->pangkat ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">TMT Pangkat</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tmt_pangkat?->format('d F Y') ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Naik Pangkat Berikutnya</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->naik_pangkat_berikutnya?->format('d F Y') ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">SK Pangkat</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->sk_pangkat ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Data KGB & Masa Kerja -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-alt"></i> Data KGB & Masa Kerja
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">KGB (Tahun)</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->kgb_tahun ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">KGB (Bulan)</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->kgb_bulan ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">TMT CPNS</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tmt_cpns?->format('d F Y') ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">TMT Pensiun</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tmt_pensiun?->format('d F Y') ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Tahun Pensiun</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tahun_pensiun ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Data Pendidikan -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-graduation-cap"></i> Data Pendidikan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Pendidikan Pertama</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->pendidikan_pertama_saat_masuk_pns ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Pendidikan Terakhir</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->pendidikan_terakhir ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Jurusan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->jurusan ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Almamater</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->almamater ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Tahun Lulus</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->tahun_lulus ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg lg:col-span-3">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Riwayat Pendidikan Formal</p>
                    <p class="font-medium text-gray-900 mt-1 whitespace-pre-wrap">{{ $pegawai->riwayat_pendidikan_formal ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Data Instansi -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-landmark"></i> Data Instansi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Instansi Asal</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->instansi_asal ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Instansi Induk</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->instansi_induk ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Riwayat Instansi Pegawai</p>
                    <p class="font-medium text-gray-900 mt-1 whitespace-pre-wrap">{{ $pegawai->riwayat_instansi_pegawai ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Data Alamat & Kontak -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-map-marker-alt"></i> Data Alamat & Kontak
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Alamat Saat Ini</p>
                    <p class="font-medium text-gray-900 mt-1 whitespace-pre-wrap">{{ $pegawai->alamat_saat_ini ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Alamat KTP</p>
                    <p class="font-medium text-gray-900 mt-1 whitespace-pre-wrap">{{ $pegawai->alamat_ktp ?? '-' }}</p>
                </div>
            </div>
        </div>

        @if($user->role === 'admin')
        <!-- Data Keuangan (Admin Only) -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-wallet"></i> Data Keuangan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-brand-biru">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">NPWP</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->npwp ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-green-500">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">No. Rekening BNI</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->no_rek_bni ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">No. Rekening BRI</p>
                    <p class="font-medium font-mono text-gray-900 mt-1">{{ $pegawai->no_rek_bri ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Operator Keuangan</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->operator_keuangan ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Data Operator -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-user-cog"></i> Data Operator
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-wide">Operator All Unit</p>
                    <p class="font-medium text-gray-900 mt-1">{{ $pegawai->operator_all_unit ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endif
        
        @endif
        
        <!-- Info Tambahan -->
        <div class="mt-8 pt-6 border-t text-xs text-gray-500">
            <div class="flex flex-wrap gap-4">
                <span><i class="fas fa-user-plus mr-1"></i> Dibuat: {{ $pegawai->created_at?->format('d M Y H:i') ?? '-' }}</span>
                <span><i class="fas fa-edit mr-1"></i> Terakhir Update: {{ $pegawai->updated_at?->format('d M Y H:i') ?? '-' }}</span>
                @if($pegawai->created_by)
                <span><i class="fas fa-user mr-1"></i> Oleh: ID {{ $pegawai->created_by }}</span>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Footer Actions -->
    <div class="px-6 py-4 bg-gray-50 border-t flex flex-wrap gap-3">
        @if(in_array($user->role, ['admin', 'kepegawaian']))
        <a href="{{ route('pegawai.edit', $pegawai->id) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-brand-biru text-white rounded-lg hover:bg-brand-biru/90 transition">
            <i class="fas fa-edit"></i>
            Edit Data
        </a>
        @endif
        @if($user->role === 'admin')
        <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" class="inline" 
              onsubmit="return confirm('Yakin ingin menghapus data pegawai ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-trash"></i>
                Hapus
            </button>
        </form>
        @endif
        <a href="{{ route('pegawai.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
