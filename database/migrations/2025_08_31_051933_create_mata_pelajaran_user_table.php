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
        Schema::create('mata_pelajaran_user', function (Blueprint $table) {
            // Kolom ini akan menjadi foreign key ke tabel users (guru)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kolom ini akan menjadi foreign key ke tabel mata_pelajarans
            $table->foreignId('mata_pelajaran_id')->constrained()->onDelete('cascade');

            // Menetapkan primary key gabungan untuk mencegah duplikasi data
            // (seorang guru tidak bisa ditugaskan ke mapel yang sama dua kali)
            $table->primary(['user_id', 'mata_pelajaran_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran_user');
    }
};
