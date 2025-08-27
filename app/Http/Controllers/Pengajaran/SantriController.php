<?php
    
    namespace App\Http\Controllers\Pengajaran;
    
    use App\Http\Controllers\Controller;
    use App\Models\Kelas;
    use App\Models\Santri;
    use App\Http\Requests\Pengajaran\StoreSantriRequest;
    use App\Http\Requests\Pengajaran\UpdateSantriRequest;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Illuminate\Http\Request;
    
    class SantriController extends Controller
    {
        use AuthorizesRequests;
    
        public function index(Request $request, Kelas $kelas) // <-- Tambahkan Request $request
    {
        $this->authorize('viewAny', Santri::class);

        // Mulai query dari relasi santris di dalam kelas
        $query = $kelas->santris();

        // Terapkan filter pencarian jika ada
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                  ->orWhere('nis', 'like', "%{$searchTerm}%");
            });
        }

        $santris = $query->latest()->paginate(10)->withQueryString();
        
        return view('pengajaran.santri.index', compact('santris', 'kelas'));
    }
    
        public function create(Kelas $kelas)
        {
            $this->authorize('create', Santri::class);
            return view('pengajaran.santri.create', compact('kelas'));
        }
    
        public function store(StoreSantriRequest $request)
        {
            $this->authorize('create', Santri::class);
            $validated = $request->validated();
    
            if ($request->hasFile('foto')) {
                // PERBAIKAN: Simpan file ke disk 'public' di dalam folder 'foto_santri'
                $path = $request->file('foto')->store('foto_santri', 'public');
                $validated['foto'] = $path;
            }
    
            $santri = Santri::create($validated);
    
            return redirect()->route('pengajaran.santris.index', $santri->kelas_id)->with('success', 'Data santri berhasil ditambahkan.');
        }
    
        public function edit(Santri $santri)
        {
            $this->authorize('update', $santri);
            return view('pengajaran.santri.edit', compact('santri'));
        }
    
        public function update(UpdateSantriRequest $request, Santri $santri)
        {
            $this->authorize('update', $santri);
            $validated = $request->validated();
    
            if ($request->hasFile('foto')) {
                // PERBAIKAN: Hapus file lama dari disk 'public'
                if ($santri->foto) {
                    Storage::disk('public')->delete($santri->foto);
                }
                // PERBAIKAN: Simpan file baru ke disk 'public'
                $path = $request->file('foto')->store('foto_santri', 'public');
                $validated['foto'] = $path;
            }
    
            $santri->update($validated);
    
            return redirect()->route('pengajaran.santris.index', $santri->kelas_id)->with('success', 'Data santri berhasil diperbarui.');
        }
    
        public function destroy(Santri $santri)
        {
            $this->authorize('delete', $santri);
            $kelas_id = $santri->kelas_id;
            
            if ($santri->foto) {
                // PERBAIKAN: Hapus file dari disk 'public'
                Storage::disk('public')->delete($santri->foto);
            }
            
            $santri->delete();
    
            return redirect()->route('pengajaran.santris.index', $kelas_id)->with('success', 'Data santri berhasil dihapus.');
        }
    }
