<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
            $table->foreignId('user_id')->comment('Merujuk ke user pengajar')->constrained('users')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->tinyInteger('day_of_week');
            $table->tinyInteger('time_slot');
            $table->string('version_id')->comment('ID unik untuk setiap versi jadwal, cth: GANJIL-2024');
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            // Mencegah bentrok di kelas yang sama
            $table->unique(['kelas_id', 'day_of_week', 'time_slot', 'version_id'], 'schedule_kelas_unique');
            // Mencegah bentrok guru
            $table->unique(['user_id', 'day_of_week', 'time_slot', 'version_id'], 'schedule_teacher_unique');
            // Mencegah bentrok ruangan
            $table->unique(['room_id', 'day_of_week', 'time_slot', 'version_id'], 'schedule_room_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
