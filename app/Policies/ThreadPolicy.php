<?php

namespace laravel\Policies;

use laravel\User;
use laravel\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     */

    public function before($user)
    {
        // todo: give the admin the for authority by return true after check

    }

    /**
     * Determine whether the user can view the thread.
     *
     * @param  \laravel\User  $user
     * @param  \laravel\Thread  $thread
     * @return mixed
     */
    public function view(User $user, Thread $thread)
    {
        //
    }

    /**
     * Determine whether the user can create threads.
     *
     * @param  \laravel\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \laravel\User  $user
     * @param  \laravel\Thread  $thread
     * @return mixed
     */
    public function update(User $user, Thread $thread)
    {

        return $thread->isAuthorized($user->id);
    }

    /**
     * Determine whether the user can delete the thread.
     *
     * @param  \laravel\User  $user
     * @param  \laravel\Thread  $thread
     * @return mixed
     */
    public function delete(User $user, Thread $thread)
    {
        //
    }
}
