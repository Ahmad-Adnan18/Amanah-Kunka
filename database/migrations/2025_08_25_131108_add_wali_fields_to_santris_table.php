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
            Schema::table('santris', function (Blueprint $table) {
                // Kolom untuk menghubungkan ke user wali, bisa null karena tidak semua santri langsung punya wali terdaftar
                $table->foreignId('wali_id')->nullable()->after('id')->constrained('users')->onDelete('set null');

                // Kolom untuk kode registrasi unik, juga bisa null
                $table->string('kode_registrasi_wali')->nullable()->unique()->after('wali_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('santris', function (Blueprint $table) {
                $table->dropForeign(['wali_id']);
                $table->dropColumn('wali_id');
                $table->dropColumn('kode_registrasi_wali');
            });
        }
    };
    