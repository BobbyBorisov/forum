<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;

class ThreadsRepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store', 'destroy']);
    }

    public function index(Channel $channel, Thread $thread)
    {
        return $thread->replies()->paginate(1);
    }

    public function store(Channel $channel, Thread $thread)
    {
        request()->validate([
            'body' => 'required'
        ]);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->user()->id
        ])->load('owner');



        return response([], 201);
    }

    public function update(Reply $reply)
    {
        request()->validate([
            'body' => 'required'
        ]);

        $reply->update(['body' => request('body')]);

        return response([], 204);
    }

    public function destroy(Channel $channel, Thread $thread, Reply $reply)
    {
        if($reply->owner->id != auth()->user()->id) return response([], 403);

        $reply->delete();
    }
}
