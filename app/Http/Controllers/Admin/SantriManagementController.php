<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas; // <-- Tambahkan ini
use App\Models\Santri;
use Illuminate\Http\Request;
use App\Imports\SantriImport;
use Maatwebsite\Excel\Facades\Excel;

class SantriManagementController extends Controller
{
    public function index(Request $request)
    {
        // Otorisasi bisa ditambahkan di sini jika perlu

        // --- PENGAMBILAN DATA UNTUK FILTER & KARTU STATISTIK ---
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        // Ambil daftar rayon yang unik dari database
        $rayonList = Santri::select('rayon')->whereNotNull('rayon')->distinct()->orderBy('rayon')->pluck('rayon');

        // --- KALKULASI UNTUK KARTU STATISTIK ---
        $stats = [
            'total' => Santri::count(),
            'putra' => Santri::where('jenis_kelamin', 'Putra')->count(),
            'putri' => Santri::where('jenis_kelamin', 'Putri')->count(),
            'tanpa_nis' => Santri::whereNull('nis')->count(),
            'tanpa_rayon' => Santri::whereNull('rayon')->count(),
        ];

        // --- LOGIKA FILTER & PENCARIAN ---
        $query = Santri::with('kelas')->latest();

        // Filter Pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                  ->orWhere('nis', 'like', "%{$searchTerm}%");
            });
        }

        // Filter Lanjutan
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('rayon')) {
            $query->where('rayon', $request->rayon);
        }
        if ($request->filled('status_data')) {
            if ($request->status_data == 'tanpa_nis') {
                $query->whereNull('nis');
            } elseif ($request->status_data == 'tanpa_rayon') {
                $query->whereNull('rayon');
            }
        }

        $santris = $query->paginate(20)->withQueryString();
        
        return view('admin.santri-management.index', compact('santris', 'kelasList', 'rayonList', 'stats'));
    }

    public function showImportForm()
    {
        return view('admin.santri-management.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new SantriImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
             }
             return redirect()->back()->with('import_errors', $errorMessages);
        }

        return redirect()->route('admin.santri-management.index')->with('success', 'Data santri berhasil diimpor.');
    }
}
