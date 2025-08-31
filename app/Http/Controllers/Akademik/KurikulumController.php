<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\KurikulumTemplate;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function index()
    {
        $templates = KurikulumTemplate::withCount('mataPelajarans')->orderBy('nama_template')->get();
        $kelasList = Kelas::with('kurikulumTemplate')->orderBy('nama_kelas')->get();
        return view('akademik.kurikulum.index', compact('templates', 'kelasList'));
    }

    public function storeTemplate(Request $request)
    {
        $request->validate(['nama_template' => 'required|string|max:255|unique:kurikulum_templates']);
        KurikulumTemplate::create($request->all());
        return back()->with('success', 'Template kurikulum berhasil dibuat.');
    }

    public function editTemplate(KurikulumTemplate $template)
    {
        $allMapel = MataPelajaran::orderBy('nama_pelajaran')->get();
        $assignedMapelIds = $template->mataPelajarans->pluck('id');
        return view('akademik.kurikulum.edit-template', compact('template', 'allMapel', 'assignedMapelIds'));
    }

    public function updateTemplate(Request $request, KurikulumTemplate $template)
    {
        $request->validate(['mata_pelajaran_ids' => 'nullable|array']);
        $template->mataPelajarans()->sync($request->mata_pelajaran_ids ?? []);
        return redirect()->route('akademik.kurikulum.index')->with('success', 'Template kurikulum berhasil diperbarui.');
    }

    public function destroyTemplate(KurikulumTemplate $template)
    {
        $template->delete();
        return back()->with('success', 'Template kurikulum berhasil dihapus.');
    }
    
    public function applyTemplate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:kurikulum_templates,id',
            'kelas_ids' => 'required|array|min:1',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $template = KurikulumTemplate::findOrFail($request->template_id);
        
        foreach ($request->kelas_ids as $kelasId) {
            $kelas = Kelas::find($kelasId);
            $kelas->kurikulumTemplate()->associate($template);
            $kelas->save();
            $kelas->mataPelajarans()->sync($template->mataPelajarans->pluck('id'));
        }

        return back()->with('success', 'Template kurikulum berhasil diterapkan.');
    }

    public function getMapelJson(Kelas $kelas)
    {
        // [PERBAIKAN] Logika query disederhanakan karena pivot sudah diatur di Model.
        $mapelForClass = $kelas->mataPelajarans()->with('teachers')->get();
        
        $response = $mapelForClass->map(function ($mapel) {
            return [
                'id' => $mapel->id,
                'nama_pelajaran' => $mapel->nama_pelajaran,
                'assigned' => true,
                'assigned_teacher_id' => $mapel->pivot->user_id, // Mengakses data pivot
                'teachers' => $mapel->teachers->map(fn($teacher) => ['id' => $teacher->id, 'name' => $teacher->name]),
            ];
        });

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'kurikulum' => 'nullable|array',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $syncData = [];

        if ($request->has('kurikulum')) {
            foreach ($request->kurikulum as $mapelId => $details) {
                if(isset($details['assigned']) && $details['assigned']) {
                    $syncData[$mapelId] = ['user_id' => $details['teacher_id'] ?? null];
                }
            }
        }
        
        $kelas->mataPelajarans()->sync($syncData);

        return back()->with('success', 'Kurikulum untuk kelas ' . $kelas->nama_kelas . ' berhasil diperbarui.');
    }
}

