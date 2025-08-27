<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return null; // Lanjutkan ke pengecekan method lain jika bukan admin
    }

    /**
     * Hanya admin yang bisa melihat daftar user.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya admin yang bisa membuat user baru.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya admin yang bisa mengupdate user.
     */
    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya admin yang bisa menghapus user.
     * Admin tidak bisa menghapus dirinya sendiri.
     */
    public function delete(User $user, User $model): bool
    {
        // Mencegah admin menghapus akunnya sendiri
        if ($user->id === $model->id) {
            return false;
        }
        return $user->role === 'admin';
    }
}
