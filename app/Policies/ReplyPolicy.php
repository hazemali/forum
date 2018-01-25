<?php

namespace laravel\Policies;

use laravel\Reply;
use laravel\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the thread.
     *
     * @param  \laravel\User $user
     * @param Reply $reply
     * @return mixed
     */
    public function update(User $user, Reply $reply)
    {

        return $reply->isAuthorized($user->id);
    }


    public function create(User $user )
    {

        $lastReply = $user->fresh()->lastReply ;
        if(!$lastReply) return true ;

        return ! $user->lastReply->wasJustPublished() ;
    }
}
