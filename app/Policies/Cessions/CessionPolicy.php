<?php

namespace App\Policies\Cessions;

use App\Models\Cessions\Cession;
use App\Models\Users\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class CessionPolicy
{
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
    public function view(User $user, Cession $cession): bool
    {
        if ($user->id === $cession->id_user) {
            return true;
        } else if ($cession->assignment && $user->id === $cession->assignment->id_user) {
            return true;
        } else if ($user->isAdminLocal() && $cession->id_tpi === $user->id_tpi) {
            return true;
        } else {
            return false;
        }
        // return ($user->id === $cession->id_user) || ($user->id === $cession->assignment->id_user) || ($user->isMinistere() && $cession->id_tpi === $user->id_tpi);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isGreffier();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cession $cession): bool
    {
        return $user->id === $cession->id_user;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cession $cession): bool
    {
        return $user->id === $cession->id_user;
    }

    public function store(User $user, Cession $cession): bool
    {
        return $user->id === $cession->id_user;
    }


    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, Cession $cession): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Cession $cession): bool
    // {
    //     //
    // }
}
