<?php
/**
 * Created by PhpStorm.
 * User: bobbyborisov
 * Date: 12/15/17
 * Time: 1:34 PM
 */

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
        return array_map('json_decode',Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    public function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}