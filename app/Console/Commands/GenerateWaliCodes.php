<?php

namespace App\Console\Commands;

use App\Models\Santri;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateWaliCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-wali-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat kode registrasi unik untuk semua santri yang belum memilikinya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses pembuatan kode registrasi wali...');

        // Cari semua santri yang belum memiliki kode registrasi
        $santrisToUpdate = Santri::whereNull('kode_registrasi_wali')->get();

        if ($santrisToUpdate->isEmpty()) {
            $this->info('Semua santri sudah memiliki kode registrasi. Tidak ada yang perlu dibuat.');
            return 0; // Selesai tanpa ada perubahan
        }

        $bar = $this->output->createProgressBar($santrisToUpdate->count());
        $bar->start();

        foreach ($santrisToUpdate as $santri) {
            // Buat kode unik (contoh: WALI- seguito da 8 caratteri alfanumerici casuali)
            $uniqueCode = 'WALI-' . strtoupper(Str::random(8));

            // Pastikan kode benar-benar unik (meskipun kemungkinannya sangat kecil untuk duplikat)
            while (Santri::where('kode_registrasi_wali', $uniqueCode)->exists()) {
                $uniqueCode = 'WALI-' . strtoupper(Str::random(8));
            }

            $santri->kode_registrasi_wali = $uniqueCode;
            $santri->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Proses selesai. Berhasil membuat kode untuk ' . $santrisToUpdate->count() . ' santri.');

        return 0; // Selesai dengan sukses
    }
}
