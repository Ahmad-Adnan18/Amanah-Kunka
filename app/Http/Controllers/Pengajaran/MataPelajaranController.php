<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class MataPelajaranController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', MataPelajaran::class);
        $mataPelajarans = MataPelajaran::latest()->paginate(15);
        return view('pengajaran.mata-pelajaran.index', compact('mataPelajarans'));
    }

    public function create()
    {
        $this->authorize('create', MataPelajaran::class);
        return view('pengajaran.mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', MataPelajaran::class);
        $validated = $request->validate([
            'nama_pelajaran' => 'required|string|max:255|unique:mata_pelajarans,nama_pelajaran',
            'kategori' => ['required', Rule::in(['Umum', 'Diniyah'])],
        ]);

        MataPelajaran::create($validated);

        return redirect()->route('pengajaran.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        $this->authorize('update', $mataPelajaran);
        return view('pengajaran.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $this->authorize('update', $mataPelajaran);
        $validated = $request->validate([
            'nama_pelajaran' => ['required', 'string', 'max:255', Rule::unique('mata_pelajarans')->ignore($mataPelajaran->id)],
            'kategori' => ['required', Rule::in(['Umum', 'Diniyah'])],
        ]);

        $mataPelajaran->update($validated);

        return redirect()->route('pengajaran.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $this->authorize('delete', $mataPelajaran);
        $mataPelajaran->delete();
        return redirect()->route('pengajaran.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
