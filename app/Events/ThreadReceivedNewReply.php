<?php

namespace App\Events;

use App\Reply;
use App\Thread;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreadReceivedNewReply
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Events\Reply
     */
    public $reply;

    /**
     * @var \App\Events\Thread
     */
    public $thread;

    /**
     * Create a new event instance.
     *
     * @param \App\Events\Reply $reply
     * @param \App\Events\Thread $thread
     */
    public function __construct(Reply $reply, Thread $thread)
    {
        //
        $this->reply = $reply;
        $this->thread = $thread;
    }
}
