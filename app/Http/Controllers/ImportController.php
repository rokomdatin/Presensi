<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\ImportLog;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PegawaiImport;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!in_array(auth()->user()->role, ['admin', 'kepegawaian'])) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $importLogs = ImportLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('import.index', compact('importLogs'));
    }

    public function create()
    {
        return view('import.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();

        // Buat log import
        $importLog = ImportLog::create([
            'user_id' => auth()->id(),
            'nama_file' => $fileName,
            'status' => ImportLog::STATUS_PROCESSING,
        ]);

        try {
            // Import menggunakan Maatwebsite Excel
            $import = new PegawaiImport($importLog);
            Excel::import($import, $file);

            $importLog->update([
                'status' => ImportLog::STATUS_COMPLETED,
                'total_rows' => $import->getRowCount(),
                'success_rows' => $import->getSuccessCount(),
                'failed_rows' => $import->getFailedCount(),
                'error_details' => $import->getErrors(),
            ]);

            LogAktivitas::log('import', 'pegawai', null, null, [
                'file' => $fileName,
                'total' => $import->getRowCount(),
                'success' => $import->getSuccessCount(),
                'failed' => $import->getFailedCount(),
            ]);

            return redirect()->route('import.index')
                ->with('success', "Import berhasil! {$import->getSuccessCount()} data berhasil diimport.");

        } catch (\Exception $e) {
            $importLog->update([
                'status' => ImportLog::STATUS_FAILED,
                'error_details' => ['error' => $e->getMessage()],
            ]);

            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function template()
    {
        // Download template Excel
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download(
            storage_path('app/templates/template_pegawai.xlsx'),
            'template_pegawai.xlsx',
            $headers
        );
    }
}
