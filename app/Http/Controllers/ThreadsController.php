<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Http\Middleware\RedirectIfNotConfirmed;
use App\Inspections\Spam;
use App\Policies\ThreadPolicy;
use App\Rules\SpamFree;
use App\Thread;
use App\Trending;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store','create','update');
        $this->middleware(RedirectIfNotConfirmed::class)->only(['create', 'store','destroy']);
    }

    public function index(Channel $channel, ThreadFilters $filters, Trending $trending){

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

        $trending = $trending->get();

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
    public function show(Channel $channel, Thread $thread, Trending $trending){

        if (auth()->check()){
            auth()->user()->read($thread);
        }

        $trending->push($thread);
        $thread->visits()->record();

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
            'channel_id' => request('channel_id'),
            'slug' => request('title')
        ]);

        return redirect($thread->path())->with('flash', 'Thread has been created');
    }

    /**
     * @param $channel
     * @param \App\Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update($channel, Thread $thread)
    {
        $this->authorize(ThreadPolicy::UPDATE, $thread);

        request()->validate([
           'title' => 'required',
           'body' => 'required'
        ]);

        $thread->update([
            'title' => request('title'),
            'body' => request('body')
        ]);

        return response([], 200);
    }

    public function destroy(Thread $thread)
    {
        $thread->delete();

        return redirect('/threads');
    }
}
