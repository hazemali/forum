<?php

namespace laravel\Listeners;

use laravel\Events\ThreadReceivedReply;

class NotifySubscribers
{


    /**
     * Handle the event.
     *
     * @param  ThreadReceivedReply $event
     * @return void
     */
    public function handle(ThreadReceivedReply $event)
    {

        $event->reply->thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->reply);
    }
}
