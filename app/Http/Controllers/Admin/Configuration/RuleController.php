<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlockedTime;
use App\Models\HourPriority;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;
use Exception;

class RuleController extends Controller
{
    /**
     * Menampilkan halaman untuk mengatur semua aturan penjadwalan.
     */
    public function index()
    {
        // Data untuk form Waktu Terblokir
        $blockedTimes = BlockedTime::all()->keyBy(fn($item) => $item->day_of_week . '-' . $item->time_slot);

        // Data untuk form Prioritas Jam
        $hourPriorities = HourPriority::all()->keyBy(fn($item) => $item->subject_category . '-' . $item->day_of_week . '-' . $item->time_slot);
        $subjectCategories = MataPelajaran::pluck('kategori')->unique()->filter();

        // Variabel umum
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7); // Jam ke 1 s/d 7

        return view('admin.configuration.rules.index', compact(
            'blockedTimes',
            'hourPriorities',
            'subjectCategories',
            'days',
            'timeSlots'
        ));
    }

    /**
     * Menyimpan semua perubahan aturan.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Simpan Waktu Terblokir
            // [MODIFIKASI] Menggunakan delete() yang aman di dalam transaksi
            BlockedTime::query()->delete(); 
            if ($request->has('blocked_times')) {
                $blockedData = [];
                foreach ($request->blocked_times as $day => $slots) {
                    foreach (array_keys($slots) as $slot) {
                        $blockedData[] = ['day_of_week' => $day, 'time_slot' => $slot, 'created_at' => now(), 'updated_at' => now()];
                    }
                }
                BlockedTime::insert($blockedData);
            }

            // 2. Simpan Prioritas Jam
            // [MODIFIKASI] Menggunakan delete() yang aman di dalam transaksi
            HourPriority::query()->delete(); 
            if ($request->has('priorities')) {
                $priorityData = [];
                foreach ($request->priorities as $category => $days) {
                    foreach ($days as $day => $slots) {
                        foreach (array_keys($slots) as $slot) {
                            $priorityData[] = [
                                'subject_category' => $category,
                                'day_of_week' => $day,
                                'time_slot' => $slot,
                                'is_allowed' => true,
                                'created_at' => now(), 
                                'updated_at' => now()
                            ];
                        }
                    }
                }
                HourPriority::insert($priorityData);
            }

            DB::commit();

            return redirect()->route('admin.rules.index')->with('success', 'Aturan penjadwalan berhasil disimpan.');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.rules.index')->with('error', 'Gagal menyimpan aturan: ' . $e->getMessage());
        }
    }
}

