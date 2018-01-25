<?php

namespace laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReplyWasFavoried extends Notification
{
    use Queueable;
    /**
     * @var
     */
    private $reply;
    /**
     * @var
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param $reply
     * @param $user
     */
    public function __construct($reply , $user)
    {
        //
        $this->reply = $reply;
        $this->user = $user;
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
            'message' => $this->user->name . ' favoried your reply on '. $this->reply->thread->title,
            'link' => $this->reply->path()
        ];
    }
}
