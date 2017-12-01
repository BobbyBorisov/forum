<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\CreateReply;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Inspections\Spam;
use App\Rules\SpamFree;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class ThreadsRepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store', 'destroy']);
    }

    public function index(Channel $channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param \App\Channel $channel
     * @param \App\Thread $thread
     * @param \App\Http\Requests\CreateReply $form
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @internal param \App\Inspections\Spam $spam
     */
    public function store(Channel $channel, Thread $thread, CreateReply $form)
    {
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->user()->id
        ])->load('owner');

        return response($reply, 201);
    }

    public function update(Reply $reply, Spam $spam)
    {
        request()->validate([
            'body' => ['required', new SpamFree]
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
