<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicScheduleController extends Controller
{
    /**
     * Menampilkan halaman utama untuk melihat jadwal per kelas atau per guru.
     */
    public function index(): View
    {
        // Ambil semua data yang diperlukan
        $classes = Kelas::where('is_active_for_scheduling', true)->orderBy('nama_kelas')->get();
        
        // Ambil semua user yang merupakan pengajar (bukan wali santri)
        $teachers = User::where('role', '!=', 'wali_santri')->orderBy('name')->get();
        
        $schedules = Schedule::with(['subject', 'teacher', 'room', 'kelas'])->get();

        // Siapkan data jadwal dalam format yang mudah diakses oleh JavaScript/AlpineJS
        $scheduleData = [
            'byClass' => $this->formatScheduleForJs($schedules, 'kelas_id'),
            'byTeacher' => $this->formatScheduleForJs($schedules, 'teacher_id'),
        ];

        // [PERBAIKAN] Mendefinisikan hari sekolah dari Sabtu sampai Kamis
        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);

        return view('jadwal.public.index', compact('classes', 'teachers', 'scheduleData', 'days', 'timeSlots'));
    }

    /**
     * Helper function untuk memformat data jadwal menjadi array multi-dimensi.
     * key: 'kelas_id' atau 'teacher_id'
     */
    private function formatScheduleForJs($schedules, $key)
    {
        $formatted = [];
        foreach ($schedules as $schedule) {
            if ($schedule->{$key}) {
                 $formatted[$schedule->{$key}][$schedule->day_of_week][$schedule->time_slot] = [
                    'subject' => $schedule->subject->nama_pelajaran ?? 'N/A',
                    'teacher' => $schedule->teacher->name ?? 'N/A',
                    'class' => $schedule->kelas->nama_kelas ?? 'N/A',
                    'room' => $schedule->room->name ?? 'N/A',
                ];
            }
        }
        return $formatted;
    }
}

