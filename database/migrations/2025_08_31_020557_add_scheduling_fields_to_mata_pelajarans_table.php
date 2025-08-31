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
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            // Menambahkan kolom-kolom yang dibutuhkan untuk algoritma penjadwalan
            $table->string('category')->after('nama_pelajaran')->comment('Kategori mapel, cth: Diniyah, Umum');
            $table->integer('duration_jp')->after('category')->comment('Durasi dalam Jam Pelajaran (JP)');
            $table->boolean('requires_special_room')->after('duration_jp')->default(false)->comment('Apakah butuh ruang khusus seperti lab');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            // Hapus semua kolom yang ditambahkan jika migrasi di-rollback
            $table->dropColumn(['category', 'duration_jp', 'requires_special_room']);
        });
    }
};
