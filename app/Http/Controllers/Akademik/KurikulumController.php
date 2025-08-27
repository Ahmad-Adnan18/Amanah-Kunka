<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KurikulumTemplate;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class KurikulumController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan halaman utama kurikulum terpadu.
     */
    public function index()
    {
        $this->authorize('viewAny', Kelas::class); // Menggunakan policy dari Kelas
        $templates = KurikulumTemplate::withCount('mataPelajarans')->get();
        $kelasList = Kelas::with('kurikulumTemplate')->orderBy('nama_kelas')->get();
        return view('akademik.kurikulum.index', compact('templates', 'kelasList'));
    }

    /**
     * Menyimpan template kurikulum baru.
     */
    public function storeTemplate(Request $request)
    {
        $this->authorize('create', Kelas::class);
        $request->validate(['nama_template' => 'required|string|max:255|unique:kurikulum_templates,nama_template']);
        KurikulumTemplate::create($request->all());
        return redirect()->route('akademik.kurikulum.index')->with('success', 'Template kurikulum berhasil dibuat.');
    }

    /**
     * Menampilkan halaman untuk mengedit mata pelajaran di dalam sebuah template.
     */
    public function editTemplate(KurikulumTemplate $template)
    {
        $this->authorize('update', Kelas::class);
        $allMataPelajaran = MataPelajaran::orderBy('kategori')->orderBy('nama_pelajaran')->get();
        $assignedMapelIds = $template->mataPelajarans()->pluck('mata_pelajarans.id')->toArray();
        return view('akademik.kurikulum.edit-template', compact('template', 'allMataPelajaran', 'assignedMapelIds'));
    }

    /**
     * Memperbarui mata pelajaran di dalam sebuah template.
     */
    public function updateTemplate(Request $request, KurikulumTemplate $template)
    {
        $this->authorize('update', Kelas::class);
        $request->validate([
            'mata_pelajaran_ids' => 'nullable|array',
            'mata_pelajaran_ids.*' => 'exists:mata_pelajarans,id',
        ]);
        $template->mataPelajarans()->sync($request->input('mata_pelajaran_ids', []));
        return redirect()->route('akademik.kurikulum.index')->with('success', 'Template ' . $template->nama_template . ' berhasil diperbarui.');
    }

    /**
     * Menghapus template kurikulum.
     */
    public function destroyTemplate(KurikulumTemplate $template)
    {
        $this->authorize('update', Kelas::class); // Hanya yang bisa update yang bisa hapus
        $template->delete();
        return redirect()->route('akademik.kurikulum.index')->with('success', 'Template kurikulum berhasil dihapus.');
    }

    /**
     * Menerapkan template kurikulum ke satu atau beberapa kelas.
     */
    public function applyTemplate(Request $request)
    {
        $this->authorize('update', Kelas::class);
        $validated = $request->validate([
            'template_id' => 'required|exists:kurikulum_templates,id',
            'kelas_ids' => 'required|array|min:1',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $template = KurikulumTemplate::findOrFail($validated['template_id']);
        // [PERBAIKAN] Tentukan nama tabel saat mengambil 'id'
        $mapelIds = $template->mataPelajarans()->pluck('mata_pelajarans.id');

        foreach ($validated['kelas_ids'] as $kelasId) {
            $kelas = Kelas::find($kelasId);
            $kelas->mataPelajarans()->sync($mapelIds);
            $kelas->kurikulum_template_id = $validated['template_id'];
            $kelas->save();
        }

        return redirect()->route('akademik.kurikulum.index')->with('success', 'Template berhasil diterapkan ke ' . count($validated['kelas_ids']) . ' kelas.');
    }
    /**
     * Mengembalikan daftar mata pelajaran dalam format JSON untuk kurikulum kelas tertentu.
     */
    public function getMapelJson(Kelas $kelas)
    {
        $mapel = $kelas->mataPelajarans()->orderBy('nama_pelajaran')->get();
        return response()->json($mapel);
    }
}
