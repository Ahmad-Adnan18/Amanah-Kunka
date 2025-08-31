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
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            // Hanya hapus kolom 'category' jika ada
            if (Schema::hasColumn('mata_pelajarans', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            // Jika migrasi dibatalkan, buat kembali kolomnya agar tidak error
            if (!Schema::hasColumn('mata_pelajarans', 'category')) {
                 $table->string('category')->nullable();
            }
        });
    }
};
