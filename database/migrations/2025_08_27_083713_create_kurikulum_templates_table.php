        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            public function up(): void
            {
                Schema::create('kurikulum_templates', function (Blueprint $table) {
                    $table->id();
                    $table->string('nama_template')->unique();
                    $table->timestamps();
                });
            }

            public function down(): void
            {
                Schema::dropIfExists('kurikulum_templates');
            }
        };
        