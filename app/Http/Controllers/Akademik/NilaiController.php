<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Exports\LegerNilaiExport;
use Maatwebsite\Excel\Facades\Excel;

class NilaiController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan form filter atau tabel input nilai.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Nilai::class);

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $santris = collect();

        if ($request->filled(['kelas_id', 'mata_pelajaran_id', 'semester', 'tahun_ajaran', 'jenis_penilaian'])) {
            $santris = Santri::where('kelas_id', $request->kelas_id)
                ->with([
                    'nilai' => function ($query) use ($request) {
                        $query->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                            ->where('semester', $request->semester)
                            ->where('tahun_ajaran', $request->tahun_ajaran);
                    }
                ])
                ->orderBy('nama')
                ->get();
        }

        return view('akademik.nilai.index', compact('kelasList', 'santris'));
    }

    /**
     * Menyimpan nilai secara massal berdasarkan jenis penilaian.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Nilai::class);

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:9',
            'jenis_penilaian' => ['required', Rule::in(['nilai_tugas', 'nilai_uts', 'nilai_uas'])],
            'nilais' => 'required|array',
            'nilais.*.santri_id' => 'required|exists:santris,id',
            'nilais.*.nilai' => 'nullable|integer|min:0|max:100',
        ]);

        $kolomNilai = $validated['jenis_penilaian'];

        foreach ($validated['nilais'] as $data) {
            Nilai::updateOrCreate(
                [
                    'santri_id' => $data['santri_id'],
                    'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                    'semester' => $validated['semester'],
                    'tahun_ajaran' => $validated['tahun_ajaran'],
                ],
                [
                    'kelas_id' => $validated['kelas_id'],
                    $kolomNilai => $data['nilai'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Menangani permintaan export leger nilai ke Excel.
     */
    public function exportLeger(Request $request)
    {
        $this->authorize('viewAny', Nilai::class);

        $filters = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string|max:9',
            'jenis_penilaian' => ['required', Rule::in(['nilai_tugas', 'nilai_uts', 'nilai_uas'])],
        ]);

        $kelas = Kelas::findOrFail($filters['kelas_id']);
        $mataPelajaran = MataPelajaran::findOrFail($filters['mata_pelajaran_id']);

        $fileName = "Leger {$filters['jenis_penilaian']} - {$mataPelajaran->nama_pelajaran} - {$kelas->nama_kelas}.xlsx";

        return Excel::download(new LegerNilaiExport($filters, $kelas, $mataPelajaran), $fileName);
    }
}
