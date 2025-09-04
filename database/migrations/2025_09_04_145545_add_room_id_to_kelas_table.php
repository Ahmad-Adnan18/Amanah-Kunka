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
            // Menambahkan kolom room_id yang terhubung ke tabel rooms
            $table->foreignId('room_id')
                  ->nullable() // Boleh kosong jika kelas belum punya ruangan induk
                  ->after('kurikulum_template_id') // Posisi kolom (opsional)
                  ->constrained('rooms') // Menetapkan foreign key ke tabel 'rooms'
                  ->onDelete('set null'); // Jika ruangan dihapus, kolom ini jadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Menghapus foreign key constraint sebelum menghapus kolom
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
    }
};