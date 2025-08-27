<?php

namespace App\Http\Controllers\Pengajaran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Santri;
use App\Models\Jabatan;
use App\Models\JabatanUser;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan; // <-- Tambahkan ini
use Maatwebsite\Excel\Facades\Excel;   // <-- Tambahkan ini
use App\Exports\WaliCodesExport;      // <-- Tambahkan ini

class KelasController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Kelas::class);
        
        // Selalu ambil daftar semua kelas untuk ditampilkan
        $kelas_list = Kelas::withCount('santris')->latest()->paginate(50);
        
        $hasilPencarianSantri = collect(); // Buat collection kosong sebagai default

        // Logika BARU: Jika ada input pencarian, cari santri secara global
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
        return view('pengajaran.kelas.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Kelas::class);
        $request->validate(['nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas']);
        Kelas::create($request->all());
        return redirect()->route('pengajaran.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $this->authorize('update', $kela);

        // Ambil data yang dibutuhkan untuk form penunjukan
        $users = User::whereIn('role', ['pengajaran', 'pengasuhan', 'kesehatan', 'ustadz_umum', 'admin'])->orderBy('name')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();

        // Ambil daftar penanggung jawab yang sudah ada untuk kelas ini
        $penanggungJawab = $kela->penanggungJawab()->with('user', 'jabatan')->get();

        return view('pengajaran.kelas.edit', compact('kela', 'users', 'jabatans', 'penanggungJawab'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $this->authorize('update', $kela);
        $request->validate(['nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kela->id]);
        $kela->update($request->all());
        return redirect()->route('pengajaran.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * TAMBAHKAN METHOD INI
     * Untuk menunjuk user ke sebuah jabatan di kelas ini.
     */
    public function assignJabatan(Request $request, Kelas $kelas)
    {
        $this->authorize('update', $kelas);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        // Cek agar tidak ada duplikasi jabatan untuk user yang sama di kelas yang sama
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

    /**
     * TAMBAHKAN METHOD INI
     * Untuk menghapus penunjukan jabatan.
     */
    public function removeJabatan(JabatanUser $jabatanUser)
    {
        $this->authorize('update', $jabatanUser->kelas); // Otorisasi berdasarkan kelasnya
        $jabatanUser->delete();
        return redirect()->back()->with('success', 'Penanggung jawab berhasil dihapus.');
    }


    public function destroy(Kelas $kela)
    {
        $this->authorize('delete', $kela);
        $kela->delete();
        return redirect()->route('pengajaran.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * Mengembalikan daftar santri dalam format JSON untuk kelas tertentu.
     */
    public function getSantrisJson(Kelas $kelas)
    {
        // Otorisasi tidak diperlukan di sini karena hanya mengembalikan data
        // untuk pengguna yang sudah terotentikasi di halaman form.
        $santris = $kelas->santris()->select('id', 'nama')->orderBy('nama')->get();
        return response()->json($santris);
    }

    /**
     * TAMBAHKAN METHOD INI
     * Menangani permintaan untuk men-generate semua kode wali.
     */
    public function generateAllWaliCodes()
    {
        $this->authorize('create', Kelas::class); // Hanya role yang bisa membuat kelas yang bisa generate

        Artisan::call('app:generate-wali-codes');

        return redirect()->back()->with('success', 'Proses pembuatan kode registrasi untuk semua santri telah selesai.');
    }

    /**
     * TAMBAHKAN METHOD INI
     * Menangani permintaan export data kode wali ke Excel.
     */
    public function exportWaliCodes()
    {
        $this->authorize('viewAny', Kelas::class);

        return Excel::download(new WaliCodesExport, 'daftar-kode-registrasi-wali.xlsx');
    }
}
