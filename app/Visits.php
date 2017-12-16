<?php
/**
 * Created by PhpStorm.
 * User: bobbyborisov
 * Date: 12/16/17
 * Time: 12:57 PM
 */

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    /**
     * @var \App\Thread
     */
    private $thread;

    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function record()
    {
        Redis::incrby($this->cacheKey(),1);
    }

    public function cacheKey()
    {
        return app()->environment('testing') ? "testing_thread.{$this->thread->id}" : "thread.{$this->thread->id}";
    }
}