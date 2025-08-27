<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perizinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->string('jenis_izin'); // Contoh: Pulang, Sakit Ringan, Sakit Berat, Piket
            $table->string('kategori'); // Contoh: Pengasuhan, Kesehatan
            $table->text('keterangan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir')->nullable(); // Wajib jika jenis izinnya pulang
            $table->enum('status', ['aktif', 'selesai', 'terlambat'])->default('aktif');

            // Audit Trail
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perizinans');
    }
};
