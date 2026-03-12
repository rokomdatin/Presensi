<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistik umum
        $stats = [
            'total_asn' => Pegawai::active()->asn()->count(),
            'total_pns' => Pegawai::active()->pns()->count(),
            'total_pppk' => Pegawai::active()->pppk()->count(),
            'total_pegawai' => Pegawai::active()->count(),
        ];

        // Statistik jenis kelamin
        $stats['total_laki'] = Pegawai::active()->where('jenis_kelamin', 'Laki-laki')->count();
        $stats['total_perempuan'] = Pegawai::active()->where('jenis_kelamin', 'Perempuan')->count();

        // Absensi hari ini
        $today = Carbon::today();
        $stats['hadir_hari_ini'] = Absensi::whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();
        $stats['terlambat_hari_ini'] = Absensi::whereDate('tanggal', $today)
            ->where('terlambat', true)
            ->count();
        $stats['belum_absen'] = $stats['total_pegawai'] - Absensi::whereDate('tanggal', $today)->count();

        // Data untuk chart bulanan
        $bulanIni = Carbon::now();
        $rekapBulanan = [];
        for ($i = 0; $i < 7; $i++) {
            $tanggal = $bulanIni->copy()->subDays($i);
            $rekapBulanan[] = [
                'tanggal' => $tanggal->format('d M'),
                'hadir' => Absensi::whereDate('tanggal', $tanggal)->where('status', 'hadir')->count(),
                'tidak_hadir' => Absensi::whereDate('tanggal', $tanggal)->whereIn('status', ['izin', 'sakit', 'cuti', 'tanpa_keterangan'])->count(),
            ];
        }
        $rekapBulanan = array_reverse($rekapBulanan);

        // Data pegawai per unit kerja
        $perUnitKerja = Pegawai::active()
            ->selectRaw('unit_kerja_es_2, COUNT(*) as total')
            ->groupBy('unit_kerja_es_2')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Total users (untuk admin)
        $stats['total_users'] = User::count();

        // Jika guest, hanya tampilkan data pribadi
        if ($user->isGuest()) {
            $pegawaiPribadi = Pegawai::where('nip', $user->nip)->first();
            $absensiPribadi = Absensi::where('pegawai_id', $pegawaiPribadi?->id)
                ->orderBy('tanggal', 'desc')
                ->limit(10)
                ->get();
            
            return view('dashboard.guest', compact('user', 'pegawaiPribadi', 'absensiPribadi'));
        }

        return view('dashboard.index', compact('user', 'stats', 'rekapBulanan', 'perUnitKerja'));
    }
}
