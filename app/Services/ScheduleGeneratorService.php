<?php

namespace App\Services;

use App\Models\BlockedTime;
use App\Models\HourPriority;
use App\Models\Kelas;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\TeacherUnavailability;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ScheduleGeneratorService
{
    // ... (properti lain tetap sama) ...
    private Collection $classes;
    private Collection $rooms;
    private Collection $teacherUnavailabilities;
    private Collection $hourPriorities;
    private Collection $blockedTimes;
    private array $scheduleGrid = [];
    private array $unplacedSubjects = [];
    private array $debugLog = [];


    public function run()
    {
        DB::beginTransaction();
        try {
            $this->initialize();
            
            if ($this->classes->isNotEmpty()) {
                $this->buildSchedule();
                $this->saveSchedule();
            }

            DB::commit();

            return [
                'success' => empty($this->unplacedSubjects),
                'unplaced' => $this->unplacedSubjects,
                'log' => $this->debugLog
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $this->debugLog[] = "ERROR: " . $e->getMessage();
            return [
                'success' => false,
                'unplaced' => $this->unplacedSubjects,
                'log' => $this->debugLog
            ];
        }
    }

    private function initialize(): void
    {
        $this->scheduleGrid = [];
        $this->unplacedSubjects = [];
        $this->debugLog = ['Memulai proses generator...'];

        Schedule::query()->delete();
        $this->debugLog[] = "Jadwal lama berhasil dihapus.";
        
        $this->classes = Kelas::where('is_active_for_scheduling', true)->with('mataPelajarans.teachers')->get();
        $this->rooms = Room::all();
        $this->teacherUnavailabilities = TeacherUnavailability::all()->groupBy('teacher_id');
        $this->hourPriorities = HourPriority::all();
        $this->blockedTimes = BlockedTime::all();

        $this->debugLog[] = "Data master berhasil dimuat: " . $this->classes->count() . " kelas, " . $this->rooms->count() . " ruangan.";
    }

    private function buildSchedule(): void
    {
        foreach ($this->classes as $class) {
            $className = $class->full_name ?? $class->nama_kelas;
            $this->debugLog[] = "--- Memproses Kelas: {$className} ---";
            
            $subjectsToPlace = $this->getSubjectsForClass($class);

            foreach ($subjectsToPlace as $subjectData) {
                $subject = $subjectData['subject'];
                $specificTeacherId = $subjectData['specific_teacher_id'];
                $placed = false;

                $this->debugLog[] = "Mencoba menempatkan mapel: {$subject->nama_pelajaran} (1 JP)";

                $availableTeachers = collect();
                if ($specificTeacherId) {
                    $teacher = $subject->teachers->find($specificTeacherId);
                    if ($teacher) $availableTeachers->push($teacher);
                } else {
                    $availableTeachers = $subject->teachers;
                }

                if ($availableTeachers->isEmpty()) {
                    $this->debugLog[] = "GAGAL: Tidak ada guru yang ditugaskan/bisa mengajar '{$subject->nama_pelajaran}'.";
                    $this->unplacedSubjects[] = "{$subject->nama_pelajaran} ({$className}) - Alasan: Tidak ada guru.";
                    continue;
                }

                // [PERBAIKAN] Memastikan loop hanya berjalan untuk hari Sabtu - Kamis
                $days = collect([1, 2, 3, 4, 5, 6])->shuffle(); 
                foreach ($days as $day) {
                    $timeSlots = collect(range(1, 7))->shuffle();
                    foreach ($timeSlots as $timeSlot) {
                        if ($this->tryPlaceSubject($class, $subject, $day, $timeSlot, $availableTeachers)) {
                            $this->debugLog[] = "BERHASIL: Ditempatkan pada Hari {$day}, Jam ke-{$timeSlot}.";
                            $placed = true;
                            break 2;
                        }
                    }
                }

                if (!$placed) {
                    $this->diagnoseFailure($subject, $className, $availableTeachers);
                }
            }
        }
    }

    private function diagnoseFailure($subject, $className, $availableTeachers)
    {
        $teacherNames = $availableTeachers->pluck('name')->implode(', ');
        $isAnyTeacherEverAvailable = false;
        
        foreach ($availableTeachers as $teacher) {
            for ($d = 1; $d <= 6; $d++) {
                for ($t = 1; $t <= 7; $t++) {
                    if ($this->isTeacherAvailable($teacher->id, $d, $t)) {
                        $isAnyTeacherEverAvailable = true;
                        break 3;
                    }
                }
            }
        }

        if (!$isAnyTeacherEverAvailable) {
            $this->debugLog[] = "GAGAL: Guru yang ditugaskan ({$teacherNames}) ditandai TIDAK TERSEDIA sepanjang minggu di menu 'Ketersediaan Guru'. Aturan ini mustahil dipenuhi.";
        } else {
            $this->debugLog[] = "GAGAL: Tidak ditemukan kombinasi slot, guru, dan ruangan yang cocok untuk '{$subject->nama_pelajaran}'. Kemungkinan semua slot yang tersedia untuk guru ini sudah terisi oleh kelas lain.";
        }

        $this->unplacedSubjects[] = "{$subject->nama_pelajaran} ({$className}) - Alasan: Tidak ada slot/ruangan/guru tersedia.";
    }

    private function getSubjectsForClass(Kelas $class): Collection
    {
        $subjects = new Collection();
        foreach ($class->mataPelajarans()->withPivot('user_id')->get() as $subject) {
            for ($i = 0; $i < $subject->duration_jp; $i++) {
                $subjects->push([
                    'subject' => $subject,
                    'specific_teacher_id' => $subject->pivot->user_id
                ]);
            }
        }
        return $subjects;
    }

    private function tryPlaceSubject($class, $subject, $day, $timeSlot, $teachers): bool
    {
        foreach ($teachers->shuffle() as $teacher) {
            $availableRooms = $this->getAvailableRooms($subject);
            foreach ($availableRooms->shuffle() as $room) {
                if ($this->isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $room)) {
                    $this->placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $room);
                    return true;
                }
            }
        }
        return false;
    }

    private function isSlotAvailable($class, $subject, $day, $timeSlot, $teacher, $room): bool
    {
        return !$this->isBlockedTime($day, $timeSlot) &&
               $this->isTeacherAvailable($teacher->id, $day, $timeSlot) &&
               !$this->isClassBusy($class->id, $day, $timeSlot) &&
               !$this->isTeacherBusy($teacher->id, $day, $timeSlot) &&
               !$this->isRoomBusy($room->id, $day, $timeSlot) &&
               $this->isHourPriorityAllowed($subject->kategori, $day, $timeSlot);
    }

    private function placeSubjectInSchedule($class, $subject, $day, $timeSlot, $teacher, $room): void
    {
        $this->scheduleGrid[$day][$timeSlot]['class'][$class->id] = [
            'mata_pelajaran_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'room_id' => $room->id,
            'kelas_id' => $class->id,
            'day_of_week' => $day,
            'time_slot' => $timeSlot
        ];
        $this->scheduleGrid[$day][$timeSlot]['teacher'][$teacher->id] = true;
        $this->scheduleGrid[$day][$timeSlot]['room'][$room->id] = true;
    }

    private function saveSchedule(): void
    {
        $schedules = [];
        foreach ($this->scheduleGrid as $day => $timeSlots) {
            foreach ($timeSlots as $timeSlot => $entries) {
                if (isset($entries['class'])) {
                    foreach ($entries['class'] as $scheduleData) {
                        $schedules[] = $scheduleData;
                    }
                }
            }
        }
        
        if (!empty($schedules)) {
            foreach($schedules as $schedule) {
                Schedule::create($schedule);
            }
            $this->debugLog[] = "Jadwal berhasil dibuat dan disimpan ke database.";
        } else {
            $this->debugLog[] = "PERINGATAN: Tidak ada jadwal yang berhasil dibuat untuk disimpan.";
        }
    }

    private function getAvailableRooms($subject): Collection
    {
        if ($subject->requires_special_room) {
            return $this->rooms->where('type', 'Khusus');
        }
        return $this->rooms->where('type', 'Biasa');
    }

    private function isTeacherAvailable($teacherId, $day, $timeSlot): bool
    {
        if (isset($this->teacherUnavailabilities[$teacherId])) {
            foreach ($this->teacherUnavailabilities[$teacherId] as $unavailability) {
                if ($unavailability->day_of_week == $day && $unavailability->time_slot == $timeSlot) {
                    return false;
                }
            }
        }
        return true;
    }

    private function isClassBusy($classId, $day, $timeSlot): bool
    {
        return isset($this->scheduleGrid[$day][$timeSlot]['class'][$classId]);
    }

    private function isTeacherBusy($teacherId, $day, $timeSlot): bool
    {
        return isset($this->scheduleGrid[$day][$timeSlot]['teacher'][$teacherId]);
    }

    private function isRoomBusy($roomId, $day, $timeSlot): bool
    {
        return isset($this->scheduleGrid[$day][$timeSlot]['room'][$roomId]);
    }

    private function isHourPriorityAllowed($category, $day, $timeSlot): bool
    {
        $priority = $this->hourPriorities->where('subject_category', $category)->where('day_of_week', $day)->where('time_slot', $timeSlot)->first();
        return $priority ? $priority->is_allowed : true;
    }

    private function isBlockedTime($day, $timeSlot): bool
    {
        return $this->blockedTimes->where('day_of_week', $day)->where('time_slot', $timeSlot)->isNotEmpty();
    }
}

