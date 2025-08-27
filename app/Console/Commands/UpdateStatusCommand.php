<?php

namespace App\Console\Commands;

use App\Models\Perizinan;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-status-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbarui status perizinan yang sudah kedaluwarsa dari "aktif" menjadi "selesai"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan status perizinan...');
        $updatedCount = 0;

        // --- LOGIKA 1: Untuk izin yang PUNYA tanggal akhir (Contoh: Pulang) ---
        $expiredWithEndDate = Perizinan::where('status', 'aktif')
                                      ->whereNotNull('tanggal_akhir')
                                      ->where('tanggal_akhir', '<', Carbon::today())
                                      ->get();

        foreach ($expiredWithEndDate as $izin) {
            $izin->status = 'selesai';
            $izin->save();
            $this->info("Izin Pulang '{$izin->santri->nama}' (ID: {$izin->id}) telah selesai.");
            $updatedCount++;
        }

        // --- LOGIKA 2: Untuk izin yang TIDAK punya tanggal akhir (Contoh: Piket, Sakit Ringan) ---
        // Izin ini dianggap selesai keesokan harinya setelah tanggal mulai.
        $expiredWithoutEndDate = Perizinan::where('status', 'aktif')
                                        ->whereNull('tanggal_akhir')
                                        ->where('tanggal_mulai', '<', Carbon::today())
                                        ->get();

        foreach ($expiredWithoutEndDate as $izin) {
            $izin->status = 'selesai';
            $izin->save();
            $this->info("Izin Harian '{$izin->santri->nama}' (ID: {$izin->id}) telah selesai.");
            $updatedCount++;
        }


        if ($updatedCount > 0) {
            $this->info("Pembaruan status selesai. Total {$updatedCount} data diperbarui.");
        } else {
            $this->info('Tidak ada perizinan yang kedaluwarsa ditemukan.');
        }
    }
}
