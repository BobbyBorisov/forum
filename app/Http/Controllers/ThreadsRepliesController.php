<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Inspections\Spam;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Support\Facades\Gate;

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
     * @param Spam $spam
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Channel $channel, Thread $thread)
    {
        request()->validate([
            'body' => ['required', new SpamFree]
        ]);

        if (Gate::denies('create', new Reply)) {
            return response('You are posting too frequently. Please take a break ;)', 422);
        }

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
