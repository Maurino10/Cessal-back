<?php

namespace App\Policies\Cessions;

use App\Models\Cessions\CessionMagistrat;
use App\Models\Users\User;
use Illuminate\Auth\Access\Response;

class CessionMagistratPolicy
{
    /**
     * Determine whether the user can view any models.
     */

    /**
     * Determine whether the user can view the model.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CessionMagistrat $cessionMagistrat): bool
    {
        return $user->id === $cessionMagistrat->id_user;
    }
    
    public function action(User $user, CessionMagistrat $cessionMagistrat): bool
    {
        return $user->id === $cessionMagistrat->id_user;
    }
}
