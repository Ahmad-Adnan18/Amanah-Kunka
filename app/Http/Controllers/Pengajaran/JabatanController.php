<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class JabatanController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Jabatan::class);
        $jabatans = Jabatan::latest()->paginate(10);
        return view('pengajaran.jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        $this->authorize('create', Jabatan::class);
        return view('pengajaran.jabatan.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Jabatan::class);
        $request->validate(['nama_jabatan' => 'required|string|max:255|unique:jabatans,nama_jabatan']);
        Jabatan::create($request->all());
        return redirect()->route('pengajaran.jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        $this->authorize('update', $jabatan);
        return view('pengajaran.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $this->authorize('update', $jabatan);
        $request->validate(['nama_jabatan' => ['required', 'string', 'max:255', Rule::unique('jabatans')->ignore($jabatan->id)]]);
        $jabatan->update($request->all());
        return redirect()->route('pengajaran.jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $this->authorize('delete', $jabatan);
        $jabatan->delete();
        return redirect()->route('pengajaran.jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
