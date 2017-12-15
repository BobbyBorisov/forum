<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Http\Middleware\RedirectIfNotConfirmed;
use App\Inspections\Spam;
use App\Rules\SpamFree;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store','create');
        $this->middleware(RedirectIfNotConfirmed::class)->only(['create', 'store','destroy']);
    }

    public function index(Channel $channel, ThreadFilters $filters){

        if ($channel->exists)
        {
            $threads = $channel->threads()->latest();
        } else
        {
            $threads = Thread::latest();
        }


        $threads = $threads->with('channel')->filter($filters)->paginate(25);

        if (request()->wantsJson())
        {
            return $threads;
        }

        $trending = array_map('json_decode',Redis::zrevrange('trending_threads', 0, 4));

        return view('threads.index', compact('threads', 'trending'));
    }

    public function create()
    {
        return view('threads.create');
    }

    /**
     * @param \App\Channel $channel
     * @param \App\Thread $thread
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Channel $channel, Thread $thread){

        if (auth()->check()){
            auth()->user()->read($thread);
        }

        Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->get()
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        request()->validate([
            'title' => ['required', new SpamFree],
            'body'  => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'title' => request('title'),
            'body'  => request('body'),
            'user_id' => auth()->user()->id,
            'channel_id' => request('channel_id')
        ]);

        return redirect($thread->path())->with('flash', 'Thread has been created');
    }

    public function destroy(Thread $thread)
    {
        $thread->delete();

        return redirect('/threads');
    }
}
