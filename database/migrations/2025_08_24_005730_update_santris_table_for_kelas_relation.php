    <?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('santris', function (Blueprint $table) {
                // Hapus kolom 'kelas' yang lama
                $table->dropColumn('kelas');
                // Tambahkan kolom 'kelas_id' sebagai foreign key
                $table->foreignId('kelas_id')->after('rayon')->constrained('kelas')->onDelete('cascade');
            });
        }
    
        public function down(): void
        {
            Schema::table('santris', function (Blueprint $table) {
                $table->dropForeign(['kelas_id']);
                $table->dropColumn('kelas_id');
                $table->string('kelas'); // Kembalikan kolom lama jika di-rollback
            });
        }
    };
    