<?php

namespace App\Policies;

use App\Models\Perizinan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerizinanPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * Admin bisa melakukan segalanya.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
 
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Perizinan $perizinan): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * PERUBAHAN DI SINI: Tambahkan 'admin' ke dalam array.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'pengasuhan', 'kesehatan']);
    }

    /**
     * Determine whether the user can update the model.
     * PERUBAHAN DI SINI: Tambahkan 'admin' ke dalam array.
     */
    public function update(User $user, Perizinan $perizinan): bool
    {
        return in_array($user->role, ['admin', 'pengasuhan', 'kesehatan']);
    }

    /**
     * Determine whether the user can delete the model.
     * PERUBAHAN DI SINI: Tambahkan 'admin' ke dalam array.
     */
    public function delete(User $user, Perizinan $perizinan): bool
    {
        return in_array($user->role, ['admin', 'pengasuhan', 'kesehatan']);
    }
}
