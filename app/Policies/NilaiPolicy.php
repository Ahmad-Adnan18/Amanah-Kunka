<?php

namespace App\Policies;

use App\Models\Nilai;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NilaiPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Izinkan Pengajaran & Admin untuk melihat dan menginput nilai.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'pengajaran';
    }

    /**
     * Izinkan Pengajaran & Admin untuk membuat/menyimpan nilai.
     */
    public function create(User $user): bool
    {
        return $user->role === 'pengajaran';
    }
}
