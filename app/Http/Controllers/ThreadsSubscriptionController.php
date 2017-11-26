<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThreadsSubscriptionController extends Controller
{
    public function store($channelId, \App\Thread $thread)
    {
        $thread->subscribe(auth()->user());

        return response([], 204);
    }


    public function destroy($channelId, \App\Thread $thread)
    {
        $thread->unsubscribe(auth()->user());

        return response([], 204);
    }
}
