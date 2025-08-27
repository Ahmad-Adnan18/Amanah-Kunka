<?php

namespace App\Providers;

use App\Models\Perizinan;
use App\Models\Santri;
use App\Policies\PerizinanPolicy;
use App\Policies\SantriPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Daftarkan policy di sini
        Santri::class => SantriPolicy::class,
        Perizinan::class => PerizinanPolicy::class,
        Kelas::class => KelasPolicy::class,
        User::class => UserPolicy::class,
        Pelanggaran::class => PelanggaranPolicy::class,
        MataPelajaran::class => MataPelajaranPolicy::class,
        Nilai::class => NilaiPolicy::class,
        Jabatan::class => JabatanPolicy::class,
        CatatanHarian::class => CatatanHarianPolicy::class,
        Prestasi::class => PrestasiPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
