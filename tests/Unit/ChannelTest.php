<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_channel_has_threads()
    {
        $channel = factory(\App\Channel::class)->create();
        $thread = factory(\App\Thread::class)->create(['channel_id'=>$channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
