<?php

namespace App\Http\Controllers\Keasramaan;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PrestasiController extends Controller
{
    use AuthorizesRequests;

    public function create(Santri $santri)
    {
        $this->authorize('create', [Prestasi::class, $santri]);
        return view('keasramaan.prestasi.create', compact('santri'));
    }

    public function store(Request $request, Santri $santri)
    {
        $this->authorize('create', [Prestasi::class, $santri]);

        $validated = $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $santri->prestasis()->create([
            'nama_prestasi' => $validated['nama_prestasi'],
            'poin' => $validated['poin'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'],
            'dicatat_oleh_id' => Auth::id(),
        ]);

        return redirect()->route('santri.profil.show', $santri)->with('success', 'Catatan prestasi berhasil ditambahkan.');
    }

    public function edit(Prestasi $prestasi)
    {
        $this->authorize('update', $prestasi);
        return view('keasramaan.prestasi.edit', compact('prestasi'));
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $this->authorize('update', $prestasi);

        $validated = $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'poin' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $prestasi->update($validated);
        return redirect()->route('santri.profil.show', $prestasi->santri_id)->with('success', 'Catatan prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        $this->authorize('delete', $prestasi);
        $santriId = $prestasi->santri_id;
        $prestasi->delete();
        return redirect()->route('santri.profil.show', $santriId)->with('success', 'Catatan prestasi berhasil dihapus.');
    }
}
