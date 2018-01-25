<?php

namespace laravel\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadReceivedReply
{
    use Dispatchable , SerializesModels;
    public $reply;

    /**
     * Create a new event instance.
     *
     * @param $reply
     */
    public function __construct($reply)
    {

        $this->reply = $reply;
    }


}
