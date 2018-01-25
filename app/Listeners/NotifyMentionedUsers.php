<?php

namespace laravel\Listeners;

use laravel\Events\ThreadReceivedReply;
use laravel\Notifications\YouWereMentioned;
use laravel\User;

class NotifyMentionedUsers
{


    /**
     * Handle the event.
     *
     * @param  ThreadReceivedReply $event
     * @return void
     */
    public function handle(ThreadReceivedReply $event)
    {

        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });


    }
}
