@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="mb-6">
    <a href="{{ route('pegawai.index') }}" class="text-brand-biru hover:underline">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pegawai
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-xl font-bold text-brand-biru mb-6">Edit Data Pegawai</h2>
    
    <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Data Pribadi -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-user"></i> Data Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama dengan Gelar <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_dengan_gelar" value="{{ old('nama_dengan_gelar', $pegawai->nama_dengan_gelar) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                    @error('nama_dengan_gelar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama tanpa Gelar <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_tanpa_gelar" value="{{ old('nama_tanpa_gelar', $pegawai->nama_tanpa_gelar) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                    @error('nama_tanpa_gelar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-red-500">*</span></label>
                    <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}" maxlength="18" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent bg-gray-50" required readonly>
                    @error('nip')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">NIP tidak dapat diubah</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $pegawai->nik) }}" maxlength="16" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label>
                    <select name="status_perkawinan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                        <option value="">Pilih</option>
                        @foreach(['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati'] as $status)
                        <option value="{{ $status }}" {{ old('status_perkawinan', $pegawai->status_perkawinan) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anak</label>
                    <input type="number" name="jumlah_anak" value="{{ old('jumlah_anak', $pegawai->jumlah_anak ?? 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                    <select name="agama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                        <option value="">Pilih</option>
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                        <option value="{{ $agama }}" {{ old('agama', $pegawai->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" name="nomor_hp" value="{{ old('nomor_hp', $pegawai->nomor_hp) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Saat Ini</label>
                    <textarea name="alamat_saat_ini" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">{{ old('alamat_saat_ini', $pegawai->alamat_saat_ini) }}</textarea>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat KTP</label>
                    <textarea name="alamat_ktp" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">{{ old('alamat_ktp', $pegawai->alamat_ktp) }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Data Kepegawaian -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-id-card"></i> Data Kepegawaian
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No Urut</label>
                    <input type="number" name="no" value="{{ old('no', $pegawai->no) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Fingerprint</label>
                    <input type="text" name="kode_fingerprint" value="{{ old('kode_fingerprint', $pegawai->kode_fingerprint) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kepegawaian <span class="text-red-500">*</span></label>
                    <select name="status_kepegawaian" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent" required>
                        <option value="">Pilih</option>
                        @foreach(['PNS', 'PPPK', 'PPNPN', 'Kontrak', 'Honorer'] as $status)
                        <option value="{{ $status }}" {{ old('status_kepegawaian', $pegawai->status_kepegawaian) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kepegawaian</label>
                    <select name="jenis_kepegawaian" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                        <option value="">Pilih</option>
                        <option value="Struktural" {{ old('jenis_kepegawaian', $pegawai->jenis_kepegawaian) == 'Struktural' ? 'selected' : '' }}>Struktural</option>
                        <option value="Fungsional" {{ old('jenis_kepegawaian', $pegawai->jenis_kepegawaian) == 'Fungsional' ? 'selected' : '' }}>Fungsional</option>
                    </select>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Eselon</label>
                    <select name="eselon" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                        <option value="">Pilih</option>
                        @foreach(['I.A', 'I.B', 'II.A', 'II.B', 'III.A', 'III.B', 'IV.A', 'IV.B', 'Non Eselon'] as $eselon)
                        <option value="{{ $eselon }}" {{ old('eselon', $pegawai->eselon) == $eselon ? 'selected' : '' }}>{{ $eselon }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Jabatan</label>
                    <input type="number" name="kelas_jabatan" value="{{ old('kelas_jabatan', $pegawai->kelas_jabatan) }}" min="1" max="17" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal SK</label>
                    <input type="date" name="tanggal_sk" value="{{ old('tanggal_sk', $pegawai->tanggal_sk?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">TMT Jabatan</label>
                    <input type="date" name="tmt_jabatan" value="{{ old('tmt_jabatan', $pegawai->tmt_jabatan?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor SK Jabatan</label>
                    <input type="text" name="nomor_sk_jabatan" value="{{ old('nomor_sk_jabatan', $pegawai->nomor_sk_jabatan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Angka Kredit SK</label>
                    <input type="number" name="angka_kredit_sk" value="{{ old('angka_kredit_sk', $pegawai->angka_kredit_sk) }}" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Angka Kredit Terakhir</label>
                    <input type="number" name="angka_kredit_jabatan_fungsional_terakhir" value="{{ old('angka_kredit_jabatan_fungsional_terakhir', $pegawai->angka_kredit_jabatan_fungsional_terakhir) }}" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Riwayat Jabatan Fungsional</label>
                    <textarea name="riwayat_jabatan_fungsional" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">{{ old('riwayat_jabatan_fungsional', $pegawai->riwayat_jabatan_fungsional) }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Data Unit Kerja -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-building"></i> Data Unit Kerja
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja Eselon 1</label>
                    <input type="text" name="unit_kerja_eselon_1" value="{{ old('unit_kerja_eselon_1', $pegawai->unit_kerja_eselon_1) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja Eselon 2</label>
                    <input type="text" name="unit_kerja_es_2" value="{{ old('unit_kerja_es_2', $pegawai->unit_kerja_es_2) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja Eselon 3</label>
                    <input type="text" name="unit_kerja_es_3" value="{{ old('unit_kerja_es_3', $pegawai->unit_kerja_es_3) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja Eselon 4</label>
                    <input type="text" name="unit_kerja_es_4" value="{{ old('unit_kerja_es_4', $pegawai->unit_kerja_es_4) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
            </div>
        </div>
        
        <!-- Data Pangkat -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-medal"></i> Data Pangkat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pangkat/Golongan</label>
                    <select name="pangkat" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                        <option value="">Pilih</option>
                        @foreach(['Juru Muda', 'Juru Muda Tk.I', 'Juru', 'Juru Tk.I', 'Pengatur Muda', 'Pengatur Muda Tk.I', 'Pengatur', 'Pengatur Tk.I', 'Penata Muda', 'Penata Muda Tk.I', 'Penata', 'Penata Tk.I', 'Pembina', 'Pembina Tk.I', 'Pembina Utama Muda', 'Pembina Utama Madya', 'Pembina Utama'] as $pangkat)
                        <option value="{{ $pangkat }}" {{ old('pangkat', $pegawai->pangkat) == $pangkat ? 'selected' : '' }}>{{ $pangkat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">TMT Pangkat</label>
                    <input type="date" name="tmt_pangkat" value="{{ old('tmt_pangkat', $pegawai->tmt_pangkat?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Naik Pangkat Berikutnya</label>
                    <input type="date" name="naik_pangkat_berikutnya" value="{{ old('naik_pangkat_berikutnya', $pegawai->naik_pangkat_berikutnya?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">SK Pangkat</label>
                    <input type="text" name="sk_pangkat" value="{{ old('sk_pangkat', $pegawai->sk_pangkat) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
            </div>
        </div>
        
        <!-- Data KGB & Masa Kerja -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-alt"></i> Data KGB & Masa Kerja
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">KGB (Tahun)</label>
                    <input type="number" name="kgb_tahun" value="{{ old('kgb_tahun', $pegawai->kgb_tahun) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">KGB (Bulan)</label>
                    <input type="number" name="kgb_bulan" value="{{ old('kgb_bulan', $pegawai->kgb_bulan) }}" min="0" max="11" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">TMT CPNS</label>
                    <input type="date" name="tmt_cpns" value="{{ old('tmt_cpns', $pegawai->tmt_cpns?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">TMT Pensiun</label>
                    <input type="date" name="tmt_pensiun" value="{{ old('tmt_pensiun', $pegawai->tmt_pensiun?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Pensiun</label>
                    <input type="number" name="tahun_pensiun" value="{{ old('tahun_pensiun', $pegawai->tahun_pensiun) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
            </div>
        </div>
        
        <!-- Data Pendidikan -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-graduation-cap"></i> Data Pendidikan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Pertama Saat Masuk PNS</label>
                    <input type="text" name="pendidikan_pertama_saat_masuk_pns" value="{{ old('pendidikan_pertama_saat_masuk_pns', $pegawai->pendidikan_pertama_saat_masuk_pns) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                    <select name="pendidikan_terakhir" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                        <option value="">Pilih</option>
                        @foreach(['SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'] as $pend)
                        <option value="{{ $pend }}" {{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                    <input type="text" name="jurusan" value="{{ old('jurusan', $pegawai->jurusan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Almamater</label>
                    <input type="text" name="almamater" value="{{ old('almamater', $pegawai->almamater) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                    <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $pegawai->tahun_lulus) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Riwayat Pendidikan Formal</label>
                    <textarea name="riwayat_pendidikan_formal" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">{{ old('riwayat_pendidikan_formal', $pegawai->riwayat_pendidikan_formal) }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Data Instansi -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-landmark"></i> Data Instansi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instansi Asal</label>
                    <input type="text" name="instansi_asal" value="{{ old('instansi_asal', $pegawai->instansi_asal) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instansi Induk</label>
                    <input type="text" name="instansi_induk" value="{{ old('instansi_induk', $pegawai->instansi_induk) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Riwayat Instansi Pegawai</label>
                    <textarea name="riwayat_instansi_pegawai" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">{{ old('riwayat_instansi_pegawai', $pegawai->riwayat_instansi_pegawai) }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Data Keuangan -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-wallet"></i> Data Keuangan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NPWP</label>
                    <input type="text" name="npwp" value="{{ old('npwp', $pegawai->npwp) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening BNI</label>
                    <input type="text" name="no_rek_bni" value="{{ old('no_rek_bni', $pegawai->no_rek_bni) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening BRI</label>
                    <input type="text" name="no_rek_bri" value="{{ old('no_rek_bri', $pegawai->no_rek_bri) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operator Keuangan</label>
                    <input type="text" name="operator_keuangan" value="{{ old('operator_keuangan', $pegawai->operator_keuangan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
            </div>
        </div>
        
        <!-- Data Operator -->
        <div class="mb-8 pb-6 border-b">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-user-cog"></i> Data Operator
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operator All Unit</label>
                    <input type="text" name="operator_all_unit" value="{{ old('operator_all_unit', $pegawai->operator_all_unit) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                </div>
            </div>
        </div>
        
        <!-- Foto -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-brand-biru mb-4 flex items-center gap-2">
                <i class="fas fa-camera"></i> Foto Pegawai
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Foto Baru</label>
                    <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG. Maks: 2MB</p>
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                @if($pegawai->foto)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Saat Ini</label>
                    <img src="{{ Storage::url($pegawai->foto) }}" alt="Foto {{ $pegawai->nama_tanpa_gelar }}" class="w-32 h-32 rounded-lg object-cover border">
                    <p class="text-xs text-gray-500 mt-2">Klik upload di kiri untuk mengganti</p>
                </div>
                @endif
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" class="px-6 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
                <i class="fas fa-save mr-1"></i> Update
            </button>
            <a href="{{ route('pegawai.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Auto-format NIP (readonly, tapi tetap sanitize)
document.querySelector('input[name="nip"]')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 18);
});

// Auto-format NIK (16 digit angka)
document.querySelector('input[name="nik"]')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').substring(0, 16);
});

// Preview foto baru sebelum upload
document.querySelector('input[name="foto"]')?.addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB!');
            this.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(evt) {
            const preview = document.querySelector('#foto-preview-new');
            if (preview) {
                preview.src = evt.target.result;
                preview.classList.remove('hidden');
            }
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection
