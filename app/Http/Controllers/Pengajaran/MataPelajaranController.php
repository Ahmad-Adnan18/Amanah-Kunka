<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar dengan eager loading
        $query = MataPelajaran::with('teachers')
                    ->orderBy('tingkatan')
                    ->orderBy('nama_pelajaran');

        // Filter berdasarkan tingkatan jika ada parameter
        if ($request->has('tingkatan') && $request->tingkatan != '') {
            $query->where('tingkatan', $request->tingkatan);
        }

        // Pagination
        $mataPelajarans = $query->paginate(15);

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
            'tingkatan' => 'required|in:1,2,3,4,5,6,Umum', // Validasi untuk tingkatan
            'kategori' => 'required|string|max:255',
            'duration_jp' => 'required|integer|min:1',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);
        
        $mataPelajaran = MataPelajaran::create([
            'nama_pelajaran' => $request->nama_pelajaran,
            'tingkatan' => $request->tingkatan, // Menyimpan tingkatan
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
            'tingkatan' => 'required|in:1,2,3,4,5,6,Umum', // Validasi untuk tingkatan
            'kategori' => 'required|string|max:255',
            'duration_jp' => 'required|integer|min:1',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        $mataPelajaran->update([
            'nama_pelajaran' => $request->nama_pelajaran,
            'tingkatan' => $request->tingkatan, // Memperbarui tingkatan
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