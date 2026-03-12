<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pegawai;
use App\Models\HariLibur;
use App\Models\PengaturanJamKerja;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Absensi::with('pegawai');

        // Guest hanya lihat absensi sendiri
        if ($user->isGuest()) {
            $pegawai = Pegawai::where('nip', $user->nip)->first();
            if ($pegawai) {
                $query->where('pegawai_id', $pegawai->id);
            }
        }

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter pegawai
        if ($request->filled('pegawai_id') && !$user->isGuest()) {
            $query->where('pegawai_id', $request->pegawai_id);
        }

        $absensi = $query->orderBy('tanggal', 'desc')
                         ->orderBy('waktu_masuk', 'desc')
                         ->paginate(20);

        $pegawaiList = $user->isGuest() ? [] : Pegawai::active()->orderBy('nama_tanpa_gelar')->get();

        return view('absensi.index', compact('absensi', 'user', 'pegawaiList'));
    }

    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $pegawai = $this->getPegawaiUser($user);

        if (!$pegawai) {
            return back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        $today = Carbon::today();

        // Cek apakah hari libur
        if (HariLibur::isLibur($today)) {
            return back()->with('error', 'Hari ini adalah hari libur.');
        }

        // Cek apakah sudah absen hari ini
        $existingAbsensi = Absensi::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existingAbsensi && $existingAbsensi->waktu_masuk) {
            return back()->with('error', 'Anda sudah melakukan clock in hari ini.');
        }

        $waktuMasuk = Carbon::now()->format('H:i:s');

        if ($existingAbsensi) {
            $existingAbsensi->update([
                'waktu_masuk' => $waktuMasuk,
                'metode_masuk' => 'web',
                'lokasi_masuk' => $request->lokasi ?? null,
                'lat_masuk' => $request->lat ?? null,
                'lng_masuk' => $request->lng ?? null,
            ]);
            $existingAbsensi->cekKeterlambatan();
            $absensi = $existingAbsensi;
        } else {
            $absensi = Absensi::create([
                'pegawai_id' => $pegawai->id,
                'tanggal' => $today,
                'waktu_masuk' => $waktuMasuk,
                'metode_masuk' => 'web',
                'lokasi_masuk' => $request->lokasi ?? null,
                'lat_masuk' => $request->lat ?? null,
                'lng_masuk' => $request->lng ?? null,
                'status' => 'hadir',
                'created_by' => $user->id,
            ]);
            $absensi->cekKeterlambatan();
        }

        LogAktivitas::log('clock_in', 'absensi', $absensi->id);

        $message = 'Clock in berhasil pada ' . $waktuMasuk;
        if ($absensi->terlambat) {
            $message .= ' (Terlambat ' . $absensi->menit_terlambat . ' menit)';
        }

        return back()->with('success', $message);
    }

    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $pegawai = $this->getPegawaiUser($user);

        if (!$pegawai) {
            return back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        $today = Carbon::today();

        $absensi = Absensi::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Anda belum melakukan clock in hari ini.');
        }

        if ($absensi->waktu_keluar) {
            return back()->with('error', 'Anda sudah melakukan clock out hari ini.');
        }

        $waktuKeluar = Carbon::now()->format('H:i:s');

        $absensi->update([
            'waktu_keluar' => $waktuKeluar,
            'metode_keluar' => 'web',
            'lokasi_keluar' => $request->lokasi ?? null,
            'lat_keluar' => $request->lat ?? null,
            'lng_keluar' => $request->lng ?? null,
        ]);

        $absensi->hitungDurasiKerja();

        LogAktivitas::log('clock_out', 'absensi', $absensi->id);

        return back()->with('success', 'Clock out berhasil pada ' . $waktuKeluar . '. Durasi kerja: ' . $absensi->durasi_kerja_format);
    }

    public function create()
    {
        $this->authorizeRole(['admin', 'kepegawaian']);
        $pegawaiList = Pegawai::active()->orderBy('nama_tanpa_gelar')->get();
        return view('absensi.create', compact('pegawaiList'));
    }

    public function store(Request $request)
    {
        $this->authorizeRole(['admin', 'kepegawaian']);

        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,cuti,dinas_luar,tanpa_keterangan',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string',
        ]);

        // Cek duplikasi
        $exists = Absensi::where('pegawai_id', $validated['pegawai_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Data absensi untuk pegawai ini pada tanggal tersebut sudah ada.');
        }

        $validated['created_by'] = auth()->id();
        $validated['metode_masuk'] = 'manual';
        $validated['metode_keluar'] = 'manual';

        $absensi = Absensi::create($validated);
        
        if ($absensi->waktu_masuk) {
            $absensi->cekKeterlambatan();
        }
        if ($absensi->waktu_masuk && $absensi->waktu_keluar) {
            $absensi->hitungDurasiKerja();
        }

        LogAktivitas::log('create', 'absensi', $absensi->id, null, $validated);

        return redirect()->route('absensi.index')
            ->with('success', 'Data absensi berhasil ditambahkan.');
    }

    public function rekap(Request $request)
    {
        $this->authorizeRole(['admin', 'kepegawaian']);

        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        $pegawaiList = Pegawai::active()->orderBy('nama_tanpa_gelar')->get();

        $rekap = [];
        foreach ($pegawaiList as $pegawai) {
            $absensi = Absensi::where('pegawai_id', $pegawai->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $rekap[] = [
                'pegawai' => $pegawai,
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'cuti' => $absensi->where('status', 'cuti')->count(),
                'dinas_luar' => $absensi->where('status', 'dinas_luar')->count(),
                'tanpa_keterangan' => $absensi->where('status', 'tanpa_keterangan')->count(),
                'total_terlambat' => $absensi->where('terlambat', true)->count(),
                'total_menit_terlambat' => $absensi->sum('menit_terlambat'),
            ];
        }

        return view('absensi.rekap', compact('rekap', 'bulan', 'tahun'));
    }

    private function getPegawaiUser($user)
    {
        if ($user->nip) {
            return Pegawai::where('nip', $user->nip)->first();
        }
        return null;
    }

    private function authorizeRole(array $roles)
    {
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }
}
