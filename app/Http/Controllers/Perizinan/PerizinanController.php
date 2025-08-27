<?php

namespace App\Http\Controllers\Perizinan;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\Santri;
use App\Http\Requests\Perizinan\StorePerizinanRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PerizinanController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Perizinan::class);
        
        // PERUBAHAN DI SINI: Tambahkan 'santri.kelas' untuk mengambil data kelas
        $perizinans = Perizinan::with('santri.kelas', 'pembuat')
            ->where('status', 'aktif')
            ->latest()
            ->paginate(10);
            
        return view('perizinan.index', compact('perizinans'));
    }

    /**
     * Show the form for creating a new resource.
     * Kita butuh parameter Santri untuk tahu siapa yang akan diizinkan.
     */
    public function create(Santri $santri)
    {
        $this->authorize('create', Perizinan::class);
        return view('perizinan.create', compact('santri'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerizinanRequest $request)
    {
        $this->authorize('create', Perizinan::class);

        $validated = $request->validated();

        // Tambahkan ID user yang sedang login sebagai pembuat izin
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'aktif';

        Perizinan::create($validated);

        return redirect()->route('dashboard')->with('success', 'Data perizinan berhasil disimpan.');
    }
    /**
         * TAMBAHKAN METHOD INI
         * Menghapus satu data perizinan.
         */
        public function destroy(Perizinan $perizinan)
        {
            $this->authorize('delete', $perizinan);
            $perizinan->delete();
            return redirect()->route('perizinan.index')->with('success', 'Catatan perizinan berhasil dihapus.');
        }

        /**
         * TAMBAHKAN METHOD INI
         * Menghapus beberapa data perizinan sekaligus.
         */
        public function bulkDestroy(Request $request)
        {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:perizinans,id',
            ]);

            // Otorisasi dilakukan secara manual untuk setiap item
            foreach ($request->ids as $id) {
                $izin = Perizinan::findOrFail($id);
                $this->authorize('delete', $izin);
            }

            Perizinan::whereIn('id', $request->ids)->delete();
            return redirect()->route('perizinan.index')->with('success', 'Catatan perizinan yang dipilih berhasil dihapus.');
        }
}
