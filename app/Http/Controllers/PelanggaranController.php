<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Santri;
use App\Models\Kelas; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PelanggaranController extends Controller
{
    use AuthorizesRequests;

    // ... method index tidak berubah ...
    public function index()
    {
        $this->authorize('viewAny', Pelanggaran::class);
        $pelanggarans = Pelanggaran::with('santri.kelas')->latest()->paginate(15);
        return view('pelanggaran.index', compact('pelanggarans'));
    }


    public function create()
    {
        $this->authorize('create', Pelanggaran::class);
        // PERUBAHAN: Kirim daftar kelas, bukan santri
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('pelanggaran.create', compact('kelasList'));
    }

    // ... method store tidak berubah ...
    public function store(Request $request)
    {
        $this->authorize('create', Pelanggaran::class);
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'jenis_pelanggaran' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'keterangan' => 'nullable|string',
            'dicatat_oleh' => 'required|string|max:255',
        ]);

        Pelanggaran::create($validated);

        return redirect()->route('pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil disimpan.');
    }


    public function edit(Pelanggaran $pelanggaran)
    {
        $this->authorize('update', $pelanggaran);
        // PERUBAHAN: Kirim daftar kelas
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('pelanggaran.edit', compact('pelanggaran', 'kelasList'));
    }

    // ... method update & destroy tidak berubah ...
    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $this->authorize('update', $pelanggaran);
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'jenis_pelanggaran' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'keterangan' => 'nullable|string',
            'dicatat_oleh' => 'required|string|max:255',
        ]);

        $pelanggaran->update($validated);

        return redirect()->route('pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil diperbarui.');
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $this->authorize('delete', $pelanggaran);
        $pelanggaran->delete();
        return redirect()->route('pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }
}
