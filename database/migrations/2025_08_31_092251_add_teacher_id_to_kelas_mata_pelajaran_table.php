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
        Schema::table('kelas_mata_pelajaran', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan ID guru yang ditugaskan secara spesifik
            // Dibuat nullable agar bisa tetap menggunakan logika lama jika tidak diisi
            $table->foreignId('user_id')->nullable()->after('mata_pelajaran_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_mata_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
