<?php

namespace App\Policies;

use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JabatanPolicy
{
    use HandlesAuthorization;

    /**
     * Hanya user dengan role 'admin' yang bisa melakukan semua aksi.
     */
    private function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Jabatan $jabatan): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Jabatan $jabatan): bool
    {
        return $this->isAdmin($user);
    }
}
