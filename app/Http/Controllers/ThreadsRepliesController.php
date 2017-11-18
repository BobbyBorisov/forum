<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;

class ThreadsRepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store');
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
}
