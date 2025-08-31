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
            // Periksa dan tambahkan kolom 'is_active_for_scheduling' jika belum ada
            if (!Schema::hasColumn('kelas', 'is_active_for_scheduling')) {
                $table->boolean('is_active_for_scheduling')->default(true)->after('nama_kelas');
            }

            // Periksa dan tambahkan kolom 'total_jp_sehari' jika belum ada
            if (!Schema::hasColumn('kelas', 'total_jp_sehari')) {
                // Pastikan kolom 'is_active_for_scheduling' ada sebelum menambahkan kolom 'after' nya
                $afterColumn = Schema::hasColumn('kelas', 'is_active_for_scheduling') ? 'is_active_for_scheduling' : 'nama_kelas';
                $table->integer('total_jp_sehari')->default(7)->after($afterColumn);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Drop kolom jika ada (aman untuk di-rollback)
            if (Schema::hasColumn('kelas', 'is_active_for_scheduling')) {
                $table->dropColumn('is_active_for_scheduling');
            }
            if (Schema::hasColumn('kelas', 'total_jp_sehari')) {
                $table->dropColumn('total_jp_sehari');
            }
        });
    }
};
