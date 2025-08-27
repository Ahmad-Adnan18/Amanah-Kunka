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
            Schema::table('users', function (Blueprint $table) {
                // Mengubah kolom 'role' untuk menambahkan 'wali_santri' ke dalam daftar ENUM
                $table->enum('role', [
                    'admin',
                    'pengasuhan',
                    'kesehatan',
                    'pengajaran',
                    'ustadz_umum',
                    'wali_santri' // <-- Opsi baru ditambahkan
                ])->default('ustadz_umum')->change();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                // Mengembalikan definisi kolom ke kondisi semula jika di-rollback
                $table->enum('role', [
                    'admin',
                    'pengasuhan',
                    'kesehatan',
                    'pengajaran',
                    'ustadz_umum'
                ])->default('ustadz_umum')->change();
            });
        }
    };
    