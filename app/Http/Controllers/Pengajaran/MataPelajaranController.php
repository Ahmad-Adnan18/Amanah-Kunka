<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        // [PERBAIKAN] Mengganti ->get() dengan ->paginate()
        // Ini akan mengambil data dalam format halaman dan memungkinkan ->links()
        $mataPelajarans = MataPelajaran::with('teachers')
                            ->orderBy('nama_pelajaran')
                            ->paginate(15); // Menampilkan 15 data per halaman

        return view('pengajaran.mata-pelajaran.index', compact('mataPelajarans'));
    }

    public function create()
    {
        $teachers = User::where('role', '!=', 'wali_santri')->orderBy('name')->get();
        return view('pengajaran.mata-pelajaran.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelajaran' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'duration_jp' => 'required|integer|min:1',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);
        
        $mataPelajaran = MataPelajaran::create([
            'nama_pelajaran' => $request->nama_pelajaran,
            'kategori' => $request->kategori,
            'duration_jp' => $request->duration_jp,
            'requires_special_room' => $request->has('requires_special_room'),
        ]);

        if ($request->has('teacher_ids')) {
            $mataPelajaran->teachers()->sync($request->teacher_ids);
        }

        return redirect()->route('pengajaran.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        $teachers = User::where('role', '!=', 'wali_santri')->orderBy('name')->get();
        return view('pengajaran.mata-pelajaran.edit', compact('mataPelajaran', 'teachers'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $request->validate([
            'nama_pelajaran' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'duration_jp' => 'required|integer|min:1',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        $mataPelajaran->update([
            'nama_pelajaran' => $request->nama_pelajaran,
            'kategori' => $request->kategori,
            'duration_jp' => $request->duration_jp,
            'requires_special_room' => $request->has('requires_special_room'),
        ]);

        $mataPelajaran->teachers()->sync($request->teacher_ids ?? []);

        return redirect()->route('pengajaran.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();
        return redirect()->route('pengajaran.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}

