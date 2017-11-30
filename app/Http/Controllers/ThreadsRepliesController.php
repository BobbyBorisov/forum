<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Inspections\Spam;
use App\Thread;

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

    public function store(Channel $channel, Thread $thread, Spam $spam)
    {
        try {
            request()->validate([
                'body' => 'required'
            ]);

            $spam->detect(request('body'));

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->user()->id
            ])->load('owner');
        } catch (\Exception $e){
            return response('Sorry, your reply cannot be saved', 422);
        }

        return response($reply, 201);
    }

    public function update(Reply $reply, Spam $spam)
    {
        try {
            request()->validate([
                'body' => 'required'
            ]);

            $spam->detect(request('body'));

            $reply->update(['body' => request('body')]);
        } catch (\Exception $e){
            return response('Sorry, your reply cannot be saved', 422);
        }
        
        return response([], 204);
    }

    public function destroy(Channel $channel, Thread $thread, Reply $reply)
    {
        if($reply->owner->id != auth()->user()->id) return response([], 403);

        $reply->delete();
    }
}
