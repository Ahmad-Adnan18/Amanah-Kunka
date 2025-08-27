<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Pelanggaran;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        // Logika untuk Wali Santri
        if ($user->role === 'wali_santri') {
            $santri = $user->santri()->with(['kelas', 'perizinans', 'pelanggarans'])->first();
            if (!$santri) {
                return view('wali.pending');
            }
            return view('wali.dashboard', compact('santri'));
        }

        // --- Logika untuk Dashboard Admin/Ustadz ---

        // Data Statistik Utama
        $totalSantri = Santri::count();
        // [PERBAIKAN] Menghapus 'where('status', 'aktif')' karena tidak ada di tabel santri
        $totalSantriPutra = Santri::where('jenis_kelamin', 'Putra')->count();
        $totalSantriPutri = Santri::where('jenis_kelamin', 'Putri')->count();
        $totalIzinAktif = Perizinan::where('status', 'aktif')->count();
        $totalSakit = Perizinan::where('status', 'aktif')->where('kategori', 'Kesehatan')->count();
        $santriPulangHariIni = Perizinan::where('status', 'aktif')->where('jenis_izin', 'like', '%Pulang%')->whereDate('tanggal_mulai', today())->count();

        // Menghitung santri yang terlambat kembali
        $jumlahTerlambat = Perizinan::where('status', 'aktif')
            ->whereNotNull('tanggal_akhir')
            ->whereDate('tanggal_akhir', '<', today())
            ->count();

        // Data untuk Tabel
        $perizinanAktif = Perizinan::with('santri.kelas')->where('status', 'aktif')->latest()->take(5)->get();
        $pelanggaranTerbaru = Pelanggaran::with('santri.kelas')->latest()->take(5)->get();

        // Data untuk Grafik
        $izinData = Perizinan::where('status', 'aktif')->select('jenis_izin', DB::raw('count(*) as total'))->groupBy('jenis_izin')->pluck('total', 'jenis_izin');
        $chartLabels = $izinData->keys();
        $chartData = $izinData->values();

        return view('dashboard', compact(
            'totalSantri',
            'totalSantriPutra', // <-- Kirim data baru
            'totalSantriPutri', // <-- Kirim data baru
            'totalIzinAktif',
            'santriPulangHariIni',
            'totalSakit',
            'jumlahTerlambat',
            'perizinanAktif',
            'pelanggaranTerbaru',
            'chartLabels',
            'chartData'
        ));
    }
}
