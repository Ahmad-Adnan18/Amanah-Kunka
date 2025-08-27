<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PlacementController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan halaman alat penempatan kelas.
     */
    public function index(Request $request)
    {
        // Otorisasi: Hanya Admin dan Pengajaran yang boleh mengakses
        $this->authorize('viewAny', Kelas::class);

        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $santris = collect(); // Default collection kosong

        // Jika ada filter kelas asal, tampilkan santri dari kelas tersebut
        if ($request->filled('source_kelas_id')) {
            $santris = Santri::where('kelas_id', $request->source_kelas_id)->orderBy('nama')->get();
        }

        return view('akademik.placement.index', compact('kelasList', 'santris'));
    }

    /**
     * Memproses pemindahan santri ke kelas baru.
     */
    public function place(Request $request)
    {
        $this->authorize('create', Kelas::class); // Menggunakan hak akses 'create' sebagai penanda

        $validated = $request->validate([
            'santri_ids' => 'required|array|min:1',
            'santri_ids.*' => 'exists:santris,id',
            'target_kelas_id' => 'required|exists:kelas,id',
        ]);

        // Update kelas_id untuk semua santri yang dipilih
        Santri::whereIn('id', $validated['santri_ids'])->update([
            'kelas_id' => $validated['target_kelas_id']
        ]);

        return redirect()->back()->with('success', count($validated['santri_ids']) . ' santri berhasil ditempatkan di kelas baru.');
    }
}
