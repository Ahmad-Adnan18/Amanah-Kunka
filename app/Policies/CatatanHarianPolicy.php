<?php

namespace App\Policies;

use App\Models\CatatanHarian;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatatanHarianPolicy
{
    use HandlesAuthorization;

    /**
     * Helper function to check if a user is a Wali Kelas for a specific santri.
     */
    protected function isWaliKelas(User $user, Santri $santri): bool
    {
        // Cek apakah user memiliki jabatan di kelas santri tersebut
        return $user->jabatans()
            ->where('kelas_id', $santri->kelas_id)
            ->where('tahun_ajaran', date('Y') . '/' . (date('Y') + 1)) // Cek untuk tahun ajaran saat ini
            ->exists();
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Hanya Wali Kelas dari santri tersebut yang bisa membuat catatan.
     */
    public function create(User $user, Santri $santri): bool
    {
        return $this->isWaliKelas($user, $santri);
    }

    /**
     * Hanya user yang membuat catatan yang bisa mengeditnya.
     */
    public function update(User $user, CatatanHarian $catatanHarian): bool
    {
        return $user->id === $catatanHarian->dicatat_oleh_id;
    }

    /**
     * Hanya user yang membuat catatan yang bisa menghapusnya.
     */
    public function delete(User $user, CatatanHarian $catatanHarian): bool
    {
        return $user->id === $catatanHarian->dicatat_oleh_id;
    }
}
