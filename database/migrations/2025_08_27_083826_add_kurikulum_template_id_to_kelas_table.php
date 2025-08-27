    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('kelas', function (Blueprint $table) {
                $table->foreignId('kurikulum_template_id')->nullable()->after('nama_kelas')->constrained('kurikulum_templates')->onDelete('set null');
            });
        }

        public function down(): void
        {
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropForeign(['kurikulum_template_id']);
                $table->dropColumn('kurikulum_template_id');
            });
        }
    };
    