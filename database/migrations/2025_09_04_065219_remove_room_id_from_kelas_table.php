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
        Schema::table('kelas', function (Blueprint $table) {
            // Hanya hapus kolom jika ada
            if (Schema::hasColumn('kelas', 'room_id')) {
                $table->dropForeign(['room_id']);
                $table->dropColumn('room_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Jika migrasi dibatalkan, buat kembali kolomnya agar tidak error
            if (!Schema::hasColumn('kelas', 'room_id')) {
                $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }
};
