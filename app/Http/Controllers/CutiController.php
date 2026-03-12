<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\SaldoCuti;
use App\Models\Absensi;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Cuti::with(['pegawai', 'approvedBy']);

        // Guest hanya lihat cuti sendiri
        if ($user->isGuest()) {
            $pegawai = Pegawai::where('nip', $user->nip)->first();
            if ($pegawai) {
                $query->where('pegawai_id', $pegawai->id);
            }
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cutiList = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('cuti.index', compact('cutiList', 'user'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->isGuest()) {
            $pegawai = Pegawai::where('nip', $user->nip)->first();
            $saldoCuti = SaldoCuti::where('pegawai_id', $pegawai?->id)
                ->where('tahun', Carbon::now()->year)
                ->first();
            return view('cuti.create', compact('pegawai', 'saldoCuti'));
        }

        $pegawaiList = Pegawai::active()->orderBy('nama_tanpa_gelar')->get();
        return view('cuti.create', compact('pegawaiList'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'pegawai_id' => $user->isGuest() ? 'nullable' : 'required|exists:pegawai,id',
            'jenis_cuti' => 'required|in:tahunan,sakit,melahirkan,besar,penting,luar_tanggungan',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
            'dokumen_pendukung' => 'nullable|file|max:5120',
        ]);

        // Jika guest, ambil pegawai_id dari user
        if ($user->isGuest()) {
            $pegawai = Pegawai::where('nip', $user->nip)->first();
            $validated['pegawai_id'] = $pegawai->id;
        }

        // Hitung jumlah hari
        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalSelesai = Carbon::parse($validated['tanggal_selesai']);
        $validated['jumlah_hari'] = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

        // Cek saldo cuti jika jenis tahunan
        if ($validated['jenis_cuti'] === 'tahunan') {
            $saldoCuti = SaldoCuti::where('pegawai_id', $validated['pegawai_id'])
                ->where('tahun', Carbon::now()->year)
                ->first();

            if (!$saldoCuti || $saldoCuti->saldo_sisa < $validated['jumlah_hari']) {
                return back()->with('error', 'Saldo cuti tidak mencukupi.');
            }
        }

        // Upload dokumen
        if ($request->hasFile('dokumen_pendukung')) {
            $validated['dokumen_pendukung'] = $request->file('dokumen_pendukung')
                ->store('dokumen-cuti', 'public');
        }

        $validated['status'] = Cuti::STATUS_PENDING;

        $cuti = Cuti::create($validated);

        LogAktivitas::log('create', 'cuti', $cuti->id, null, $validated);

        return redirect()->route('cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function approve(Request $request, $id)
    {
        $this->authorizeRole(['admin', 'kepegawaian']);

        $cuti = Cuti::findOrFail($id);

        if ($cuti->status !== Cuti::STATUS_PENDING) {
            return back()->with('error', 'Pengajuan cuti sudah diproses.');
        }

        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_approval' => 'nullable|string',
        ]);

        $cuti->update([
            'status' => $validated['status'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_approval' => $validated['catatan_approval'],
        ]);

        // Jika disetujui dan jenis tahunan, kurangi saldo
        if ($validated['status'] === Cuti::STATUS_DISETUJUI) {
            if ($cuti->jenis_cuti === 'tahunan') {
                $saldoCuti = SaldoCuti::where('pegawai_id', $cuti->pegawai_id)
                    ->where('tahun', Carbon::now()->year)
                    ->first();

                if ($saldoCuti) {
                    $saldoCuti->kurangiSaldo($cuti->jumlah_hari);
                }
            }

            // Buat record absensi dengan status cuti
            $tanggalMulai = Carbon::parse($cuti->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($cuti->tanggal_selesai);

            for ($date = $tanggalMulai; $date->lte($tanggalSelesai); $date->addDay()) {
                Absensi::updateOrCreate(
                    [
                        'pegawai_id' => $cuti->pegawai_id,
                        'tanggal' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => 'cuti',
                        'keterangan' => 'Cuti ' . $cuti->jenis_cuti,
                        'created_by' => auth()->id(),
                    ]
                );
            }
        }

        LogAktivitas::log('approve_cuti', 'cuti', $cuti->id);

        $statusText = $validated['status'] === 'disetujui' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Pengajuan cuti berhasil {$statusText}.");
    }

    private function authorizeRole(array $roles)
    {
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}
