<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teacher_unavailabilities', function (Blueprint $table) {
            // Langkah 1: Hapus foreign key constraint yang lama.
            // Laravel secara default menamainya [table]_[column]_foreign.
            $table->dropForeign(['user_id']);

            // Langkah 2: Hapus unique index yang lama.
            $table->dropUnique('teacher_unavailabilities_user_id_day_of_week_time_slot_unique');
            
            // Langkah 3: Ganti nama kolom.
            $table->renameColumn('user_id', 'teacher_id');
            
            // Langkah 4: Tambahkan kembali foreign key dengan nama kolom yang baru.
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');

            // Langkah 5: Buat kembali unique index dengan nama kolom yang baru.
            $table->unique(['teacher_id', 'day_of_week', 'time_slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_unavailabilities', function (Blueprint $table) {
            // Urutan dibalik untuk proses rollback
            $table->dropForeign(['teacher_id']);
            $table->dropUnique(['teacher_id', 'day_of_week', 'time_slot']);

            $table->renameColumn('teacher_id', 'user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'day_of_week', 'time_slot'], 'teacher_unavailabilities_user_id_day_of_week_time_slot_unique');
        });
    }
};
