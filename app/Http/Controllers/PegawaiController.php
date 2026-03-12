<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    /**
     * Validasi rules untuk pegawai (digunakan di store & update)
     */
    private function validationRules($id = null): array
    {
        $nipRule = ['required', 'string', 'max:20'];
        $nipRule[] = Rule::unique('pegawai', 'nip')
            ->ignore($id)
            ->where('is_active', true); // Hanya validasi unik untuk data aktif

        return [
            'nama_dengan_gelar' => 'required|string|max:255',
            'nama_tanpa_gelar' => 'required|string|max:255',
            'nip' => $nipRule,
            'status_kepegawaian' => 'required|string',
            'jenis_kepegawaian' => 'nullable|string',
            'no' => 'nullable|integer',
            'kode_fingerprint' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'status_perkawinan' => 'nullable|string|max:50',
            'jumlah_anak' => 'nullable|integer|min:0',
            'agama' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'eselon' => 'nullable|string|max:20',
            'kelas_jabatan' => 'nullable|integer|min:1|max:17',
            'tanggal_sk' => 'nullable|date',
            'tmt_jabatan' => 'nullable|date',
            'nomor_sk_jabatan' => 'nullable|string|max:100',
            'angka_kredit_sk' => 'nullable|numeric',
            'angka_kredit_jabatan_fungsional_terakhir' => 'nullable|numeric',
            'riwayat_jabatan_fungsional' => 'nullable|string',
            'unit_kerja_eselon_1' => 'nullable|string|max:255',
            'unit_kerja_es_2' => 'nullable|string|max:255',
            'unit_kerja_es_3' => 'nullable|string|max:255',
            'unit_kerja_es_4' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:100',
            'tmt_pangkat' => 'nullable|date',
            'naik_pangkat_berikutnya' => 'nullable|date',
            'sk_pangkat' => 'nullable|string|max:100',
            'kgb_tahun' => 'nullable|integer',
            'kgb_bulan' => 'nullable|integer|min:1|max:12',
            'tmt_cpns' => 'nullable|date',
            'tmt_pensiun' => 'nullable|date',
            'tahun_pensiun' => 'nullable|integer',
            'pendidikan_pertama_saat_masuk_pns' => 'nullable|string|max:255',
            'riwayat_pendidikan_formal' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:255',
            'almamater' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|integer',
            'riwayat_instansi_pegawai' => 'nullable|string',
            'instansi_asal' => 'nullable|string|max:255',
            'instansi_induk' => 'nullable|string|max:255',
            'alamat_saat_ini' => 'nullable|string',
            'alamat_ktp' => 'nullable|string',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'no_rek_bni' => 'nullable|string|max:50',
            'no_rek_bri' => 'nullable|string|max:50',
            'nomor_hp' => 'nullable|string|max:20',
            'operator_keuangan' => 'nullable|string|max:50',
            'operator_all_unit' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Pegawai::query();

        // Filter berdasarkan role
        if ($user->isGuest()) {
            // Guest hanya lihat data sendiri (dengan validasi NIP tidak null)
            if ($user->nip) {
                $query->where('nip', $user->nip);
            } else {
                // Jika guest tidak memiliki NIP, kembalikan hasil kosong
                $query->whereRaw('1 = 0');
            }
        }

        // Search dengan sanitasi input
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_tanpa_gelar', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        // Filter status kepegawaian
        if ($request->filled('status_kepegawaian')) {
            $query->where('status_kepegawaian', $request->status_kepegawaian);
        }

        // Filter unit kerja
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja_es_2', $request->unit_kerja);
        }

        $pegawai = $query->active()->orderBy('nama_tanpa_gelar')->paginate(15);

        // Tentukan kolom yang ditampilkan berdasarkan role
        $kolom = $this->getKolomByRole($user);

        // Get unique values untuk filter (hanya dari data aktif)
        $statusList = Pegawai::active()->distinct()->pluck('status_kepegawaian')->filter();
        $unitKerjaList = Pegawai::active()->distinct()->pluck('unit_kerja_es_2')->filter();

        return view('pegawai.index', compact('pegawai', 'kolom', 'user', 'statusList', 'unitKerjaList'));
    }

    public function show($id)
    {
        $user = auth()->user();
        // Gunakan active() scope agar tidak bisa akses data yang sudah di-deactivate
        $pegawai = Pegawai::active()->findOrFail($id);

        // Guest hanya bisa lihat data sendiri
        if ($user->isGuest() && $pegawai->nip !== $user->nip) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $kolom = $this->getKolomByRole($user);

        return view('pegawai.show', compact('pegawai', 'kolom', 'user'));
    }

    public function create()
    {
        $this->authorizeRole(['admin', 'kepegawaian']);
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $this->authorizeRole(['admin', 'kepegawaian']);

        $validated = $request->validate($this->validationRules());

        // Handle upload foto
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $validated['foto'] = $request->file('foto')->store('foto-pegawai', 'public');
        }

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = true;

        $pegawai = Pegawai::create($validated);

        LogAktivitas::log('create', 'pegawai', $pegawai->id, null, $validated);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->authorizeRole(['admin', 'kepegawaian']);
        $pegawai = Pegawai::active()->findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeRole(['admin', 'kepegawaian']);

        $pegawai = Pegawai::active()->findOrFail($id);
        $oldValues = $pegawai->toArray();

        $validated = $request->validate($this->validationRules($id));

        // Handle upload foto dengan cleanup file lama
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            // Hapus foto lama jika ada
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto-pegawai', 'public');
        }

        $validated['updated_by'] = auth()->id();

        $pegawai->update($validated);

        LogAktivitas::log('update', 'pegawai', $pegawai->id, $oldValues, $validated);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->authorizeRole(['admin']);

        // Gunakan active() scope untuk memastikan hanya data aktif yang bisa di-deactivate
        $pegawai = Pegawai::active()->findOrFail($id);
        $oldValues = $pegawai->toArray();

        // Soft delete dengan update is_active
        $pegawai->update(['is_active' => false]);

        // Hapus foto jika ada untuk menghemat storage (opsional)
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        LogAktivitas::log('delete', 'pegawai', $pegawai->id, $oldValues, null);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }

    /**
     * Helper: Tentukan kolom yang boleh diakses berdasarkan role user
     */
    private function getKolomByRole($user): array
    {
        return match (true) {
            $user->isAdmin() => ['all'],
            $user->isKepegawaian() => Pegawai::kolom_kepegawaian(),
            $user->isKeuangan() => Pegawai::kolom_keuangan(),
            default => Pegawai::kolom_guest(),
        };
    }

    /**
     * Helper: Autorisasi akses berdasarkan role
     * Menggunakan method user() untuk konsistensi dengan getKolomByRole
     */
    private function authorizeRole(array $roles): void
    {
        $user = auth()->user();
        
        // Cek apakah user memiliki salah satu role yang diizinkan
        $hasAccess = match (true) {
            in_array('admin', $roles) && $user->isAdmin() => true,
            in_array('kepegawaian', $roles) && $user->isKepegawaian() => true,
            in_array('keuangan', $roles) && $user->isKeuangan() => true,
            default => in_array($user->role ?? '', $roles),
        };

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}