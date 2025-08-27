        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            public function up(): void
            {
                Schema::create('catatan_harians', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
                    $table->date('tanggal');
                    $table->text('catatan');
                    $table->foreignId('dicatat_oleh_id')->constrained('users')->onDelete('cascade');
                    $table->timestamps();
                });
            }

            public function down(): void
            {
                Schema::dropIfExists('catatan_harians');
            }
        };
        