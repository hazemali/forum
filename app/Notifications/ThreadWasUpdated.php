<?php

namespace laravel\Notifications;

use Illuminate\Notifications\Notification;

class ThreadWasUpdated extends Notification
{


    protected $thread;
    protected $reply;

    /**
     * Create a new notification instance.
     *
     * @param $thread
     * @param $reply
     */
    public function __construct($thread , $reply)
    {
        //
        $this->thread = $thread;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }



    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->reply->owner->name . ' Replied to '. $this->thread->title,
            'link' => $this->reply->path()
        ];
    }
}
