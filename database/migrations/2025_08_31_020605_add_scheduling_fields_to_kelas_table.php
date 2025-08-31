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
        Schema::table('kelas', function (Blueprint $table) {
            // Menambahkan level dan nama paralel untuk detail kelas
            $table->integer('level')->after('nama_kelas')->nullable()->comment('Tingkat kelas, cth: 7, 8, 9');
            $table->string('parallel_name')->after('level')->nullable()->comment('Nama paralel, cth: A, B, Putra A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn(['level', 'parallel_name']);
        });
    }
};
