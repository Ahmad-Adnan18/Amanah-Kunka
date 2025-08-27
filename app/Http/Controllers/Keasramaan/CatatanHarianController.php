<?php

namespace App\Http\Controllers\Keasramaan;

use App\Http\Controllers\Controller;
use App\Models\CatatanHarian;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CatatanHarianController extends Controller
{
    use AuthorizesRequests;

    public function create(Santri $santri)
    {
        $this->authorize('create', [CatatanHarian::class, $santri]);
        return view('keasramaan.catatan.create', compact('santri'));
    }

    public function store(Request $request, Santri $santri)
    {
        $this->authorize('create', [CatatanHarian::class, $santri]);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
        ]);

        $santri->catatanHarians()->create([
            'tanggal' => $validated['tanggal'],
            'catatan' => $validated['catatan'],
            'dicatat_oleh_id' => Auth::id(),
        ]);

        return redirect()->route('santri.profil.show', $santri)->with('success', 'Catatan harian berhasil ditambahkan.');
    }

    public function edit(CatatanHarian $catatan)
    {
        $this->authorize('update', $catatan);
        return view('keasramaan.catatan.edit', compact('catatan'));
    }

    public function update(Request $request, CatatanHarian $catatan)
    {
        $this->authorize('update', $catatan);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'required|string',
        ]);

        $catatan->update($validated);
        return redirect()->route('santri.profil.show', $catatan->santri_id)->with('success', 'Catatan harian berhasil diperbarui.');
    }

    public function destroy(CatatanHarian $catatan)
    {
        $this->authorize('delete', $catatan);
        $santriId = $catatan->santri_id;
        $catatan->delete();
        return redirect()->route('santri.profil.show', $santriId)->with('success', 'Catatan harian berhasil dihapus.');
    }
}
