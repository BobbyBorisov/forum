<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_increments_thread_score_each_time_it_is_read()
    {
        Redis::del('trending_threads');

        $this->assertEmpty(Redis::zrevrange('trending_threads', 0, -1));

        $thread = factory(\App\Thread::class)->create();

    	$this->get($thread->path());

        $trending = Redis::zrevrange('trending_threads', 0, -1);
        $this->assertCount(1, $trending);
    }
}
