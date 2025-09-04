<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Santri;
use App\Models\Jabatan;
use App\Models\JabatanUser;
use App\Models\Room; // [PERUBAHAN] Menambahkan model Room
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WaliCodesExport;

class KelasController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Kelas::class);
        
        // [PERUBAHAN] Menambahkan eager loading untuk relasi 'room' agar efisien
        $kelas_list = Kelas::withCount('santris')->with('room')->latest()->paginate(50);
        
        $hasilPencarianSantri = collect();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $hasilPencarianSantri = Santri::with('kelas')
                ->where('nama', 'like', "%{$searchTerm}%")
                ->orWhere('nis', 'like', "%{$searchTerm}%")
                ->get();
        }
        
        return view('pengajaran.kelas.index', compact('kelas_list', 'hasilPencarianSantri'));
    }

    public function create()
    {
        $this->authorize('create', Kelas::class);
        
        // [PERUBAHAN] Mengambil daftar ruangan untuk dikirim ke view
        $rooms = Room::orderBy('name')->get();

        return view('pengajaran.kelas.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Kelas::class);

        // [PERUBAHAN] Menambahkan validasi untuk room_id
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        // 'is_active_for_scheduling' akan otomatis terisi nilai default (0) dari database
        Kelas::create($validatedData);

        return redirect()->route('pengajaran.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $this->authorize('update', $kela);

        // Data untuk form penunjukan jabatan
        $users = User::whereIn('role', ['pengajaran', 'pengasuhan', 'kesehatan', 'ustadz_umum', 'admin'])->orderBy('name')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $penanggungJawab = $kela->penanggungJawab()->with('user', 'jabatan')->get();

        // [PERUBAHAN] Mengambil daftar ruangan untuk dikirim ke view edit
        $rooms = Room::orderBy('name')->get();

        return view('pengajaran.kelas.edit', compact('kela', 'users', 'jabatans', 'penanggungJawab', 'rooms'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $this->authorize('update', $kela);

        // [PERUBAHAN] Menambahkan validasi untuk room_id dan status penjadwalan
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kela->id,
            'room_id' => 'nullable|exists:rooms,id',
            'is_active_for_scheduling' => 'required|boolean',
        ]);

        $kela->update($validatedData);

        return redirect()->route('pengajaran.kelas.index')->with('success', 'Detail kelas berhasil diperbarui.');
    }

    public function assignJabatan(Request $request, Kelas $kelas)
    {
        $this->authorize('update', $kelas);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        JabatanUser::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'kelas_id' => $kelas->id,
                'jabatan_id' => $request->jabatan_id,
                'tahun_ajaran' => $request->tahun_ajaran,
            ],
            []
        );

        return redirect()->back()->with('success', 'Penanggung jawab berhasil ditambahkan.');
    }

    public function removeJabatan(JabatanUser $jabatanUser)
    {
        $this->authorize('update', $jabatanUser->kelas);
        $jabatanUser->delete();
        return redirect()->back()->with('success', 'Penanggung jawab berhasil dihapus.');
    }

    public function destroy(Kelas $kela)
    {
        $this->authorize('delete', $kela);
        $kela->delete();
        return redirect()->route('pengajaran.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function getSantrisJson(Kelas $kelas)
    {
        $santris = $kelas->santris()->select('id', 'nama')->orderBy('nama')->get();
        return response()->json($santris);
    }

    public function generateAllWaliCodes()
    {
        $this->authorize('create', Kelas::class);
        Artisan::call('app:generate-wali-codes');
        return redirect()->back()->with('success', 'Proses pembuatan kode registrasi untuk semua santri telah selesai.');
    }

    public function exportWaliCodes()
    {
        $this->authorize('viewAny', Kelas::class);
        return Excel::download(new WaliCodesExport, 'daftar-kode-registrasi-wali.xlsx');
    }
}