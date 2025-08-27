        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            public function up(): void
            {
                Schema::create('kurikulum_template_mata_pelajaran', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('kurikulum_template_id')->constrained('kurikulum_templates')->onDelete('cascade');
                    $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
                    $table->timestamps();
                });
            }

            public function down(): void
            {
                Schema::dropIfExists('kurikulum_template_mata_pelajaran');
            }
        };
        