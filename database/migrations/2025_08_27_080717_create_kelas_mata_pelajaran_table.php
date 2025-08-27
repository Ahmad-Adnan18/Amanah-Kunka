    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            // Tabel ini berfungsi sebagai "jembatan" antara kelas dan mata pelajaran
            Schema::create('kelas_mata_pelajaran', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
                $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('kelas_mata_pelajaran');
        }
    };
    