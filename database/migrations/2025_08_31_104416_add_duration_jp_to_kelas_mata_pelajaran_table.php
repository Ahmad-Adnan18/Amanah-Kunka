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
            // Menambahkan kolom untuk menyimpan durasi JP spesifik per kelas
            $table->unsignedInteger('duration_jp')->default(1)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_mata_pelajaran', function (Blueprint $table) {
            $table->dropColumn('duration_jp');
        });
    }
};
