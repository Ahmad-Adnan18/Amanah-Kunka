<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->string('jenis_pelanggaran');
            $table->date('tanggal_kejadian');
            $table->text('keterangan')->nullable();
            $table->string('dicatat_oleh'); // Sesuai permintaan, diisi manual
            // Catatan: Untuk pengembangan selanjutnya, ini bisa diubah menjadi
            // $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};