<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('nilais', function (Blueprint $table) {
                $table->id();
                $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
                $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
                $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
                
                // Kolom untuk menyimpan nilai
                $table->unsignedInteger('nilai_tugas')->nullable();
                $table->unsignedInteger('nilai_uts')->nullable();
                $table->unsignedInteger('nilai_uas')->nullable();
                
                // Kolom untuk konteks akademik
                $table->enum('semester', ['Ganjil', 'Genap']);
                $table->string('tahun_ajaran', 9); // Contoh: 2024/2025

                // Audit trail
                $table->foreignId('created_by')->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');

                $table->timestamps();

                // Mencegah duplikasi data nilai untuk santri, mapel, dan semester yang sama
                $table->unique(['santri_id', 'mata_pelajaran_id', 'semester', 'tahun_ajaran']);
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('nilais');
        }
    };
    