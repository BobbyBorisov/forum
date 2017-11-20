<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;

class ThreadsRepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store','destroy');
    }

    public function store(Channel $channel, Thread $thread)
    {
        request()->validate([
            'body' => 'required'
        ]);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->user()->id
        ]);

        return back();
    }

    public function destroy(Channel $channel, Thread $thread, Reply $reply)
    {
        $reply->delete();
    }
}
