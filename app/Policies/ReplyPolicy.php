<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\User $user
     * @param Reply $reply
     * @return mixed
     */
    public function update(User $user, Reply $reply)
    {

        return $reply->isAuthorized($user->id);
    }

}
