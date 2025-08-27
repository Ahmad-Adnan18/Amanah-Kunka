<?php

namespace App\Policies;

use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MataPelajaranPolicy
{
    use HandlesAuthorization;

    /**
     * Izinkan admin melakukan segalanya.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Hanya admin dan pengajaran yang bisa melihat.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'pengajaran';
    }

    /**
     * Hanya admin dan pengajaran yang bisa membuat.
     */
    public function create(User $user): bool
    {
        return $user->role === 'pengajaran';
    }

    /**
     * Hanya admin dan pengajaran yang bisa mengupdate.
     */
    public function update(User $user, MataPelajaran $mataPelajaran): bool
    {
        return $user->role === 'pengajaran';
    }

    /**
     * Hanya admin dan pengajaran yang bisa menghapus.
     */
    public function delete(User $user, MataPelajaran $mataPelajaran): bool
    {
        return $user->role === 'pengajaran';
    }
}