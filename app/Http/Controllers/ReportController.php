<?php

namespace App\Http\Controllers;

use App\Exports\PerizinanReportExport;
use App\Exports\PelanggaranReportExport;
use App\Exports\SantriExport;
use App\Models\Kelas;
use App\Models\Perizinan;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
     use AuthorizesRequests;
    // Menampilkan halaman utama Laporan (Hub)
    public function index()
    {
        return view('reports.index');
    }

    // --- LAPORAN PERIZINAN ---
    public function perizinan(Request $request)
    {
        $this->authorize('viewAny', Perizinan::class);
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $filters = $request->only(['tanggal_mulai', 'tanggal_akhir', 'kategori', 'kelas_id']);

        $query = Perizinan::with('santri.kelas', 'pembuat')->latest();
        if ($request->filled('tanggal_mulai'))
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
        if ($request->filled('tanggal_akhir'))
            $query->whereDate('tanggal_mulai', '<=', $request->tanggal_akhir);
        if ($request->filled('kategori'))
            $query->where('kategori', $request->kategori);
        if ($request->filled('kelas_id'))
            $query->whereHas('santri', fn($q) => $q->where('kelas_id', $request->kelas_id));

        $perizinans = $query->paginate(15)->withQueryString();
        return view('reports.perizinan', compact('perizinans', 'kelasList'));
    }

    public function exportPerizinan(Request $request)
    {
        $this->authorize('viewAny', Perizinan::class);
        $filters = $request->only(['tanggal_mulai', 'tanggal_akhir', 'kategori', 'kelas_id']);
        return Excel::download(new PerizinanReportExport($filters), 'laporan-perizinan.xlsx');
    }

    // --- LAPORAN PELANGGARAN ---
    public function pelanggaran(Request $request)
    {
        $this->authorize('viewAny', Pelanggaran::class);
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $filters = $request->only(['tanggal_mulai', 'tanggal_akhir', 'kelas_id']);

        $query = Pelanggaran::with('santri.kelas')->latest();
        if ($request->filled('tanggal_mulai'))
            $query->whereDate('tanggal_kejadian', '>=', $request->tanggal_mulai);
        if ($request->filled('tanggal_akhir'))
            $query->whereDate('tanggal_kejadian', '<=', $request->tanggal_akhir);
        if ($request->filled('kelas_id'))
            $query->whereHas('santri', fn($q) => $q->where('kelas_id', $request->kelas_id));

        $pelanggarans = $query->paginate(15)->withQueryString();
        return view('reports.pelanggaran', compact('pelanggarans', 'kelasList'));
    }

    public function exportPelanggaran(Request $request)
    {
        $this->authorize('viewAny', Pelanggaran::class);
        $filters = $request->only(['tanggal_mulai', 'tanggal_akhir', 'kelas_id']);
        return Excel::download(new PelanggaranReportExport($filters), 'laporan-pelanggaran.xlsx');
    }

    // --- EXPORT DATA SANTRI ---
    public function exportSantri()
    {
        // Otorisasi tidak diperlukan karena semua role boleh
        return Excel::download(new SantriExport, 'data-induk-santri.xlsx');
    }
}
