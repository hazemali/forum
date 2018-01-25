<?php

namespace laravel\Policies;

use laravel\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the thread.
     *
     * @param User $signedInUser
     * @param  \laravel\User $user
     * @return mixed
     * @internal param \laravel\Thread $thread
     */
    public function update(User $signedInUser, User $user)
    {
        return $signedInUser->id === $user->id;
    }

}
