<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\TeacherUnavailability;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TeacherAvailabilityController extends Controller
{
    public function index(): View
    {
        $teachers = User::where('role', '!=', 'wali_santri')->orderBy('name')->get();

        // [PERBAIKAN] Ambil semua ID guru yang sudah memiliki aturan.
        // Ini cara yang efisien untuk memeriksa status di halaman view.
        $unavailabilities = TeacherUnavailability::all()->pluck('teacher_id')->unique();

        return view('admin.master-data.teacher-availability.index', compact('teachers', 'unavailabilities'));
    }

    public function edit(User $teacher): View
    {
        if ($teacher->role === 'wali_santri') {
            abort(404);
        }

        $days = [1 => 'Sabtu', 2 => 'Ahad', 3 => 'Senin', 4 => 'Selasa', 5 => 'Rabu', 6 => 'Kamis'];
        $timeSlots = range(1, 7);
        
        $unavailableSlots = TeacherUnavailability::where('teacher_id', $teacher->id)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->day_of_week . '-' . $item->time_slot => true];
            });

        return view('admin.master-data.teacher-availability.edit', compact('teacher', 'days', 'timeSlots', 'unavailableSlots'));
    }

    public function update(Request $request, User $teacher): RedirectResponse
    {
        TeacherUnavailability::where('teacher_id', $teacher->id)->delete();

        if ($request->has('unavailable_slots')) {
            $slotsToInsert = [];
            foreach ($request->unavailable_slots as $slot) {
                list($day, $time) = explode('-', $slot);
                $slotsToInsert[] = [
                    'teacher_id' => $teacher->id,
                    'day_of_week' => $day,
                    'time_slot' => $time,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            TeacherUnavailability::insert($slotsToInsert);
        }

        return redirect()->route('admin.teacher-availability.index')
            ->with('success', 'Ketersediaan untuk ' . $teacher->name . ' berhasil diperbarui.');
    }
}

