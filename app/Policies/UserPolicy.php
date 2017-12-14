<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param \App\User $user
     * @param \App\User $signedInUser
     */
    public function update(User $user, User $signedInUser)
    {
        return $signedInUser->id === $user->id;
    }
}
