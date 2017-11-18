<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testThreadDoesHaveAnOwner()
    {
        $thread = factory(\App\Thread::class)->create();

        $this->assertInstanceOf(\App\User::class, $thread->creator);
    }

    /** @test */
    public function it_can_add_a_reply()
    {
        $thread = factory(\App\Thread::class)->create();

        $thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
    	$thread = factory(\App\Thread::class)->create();

    	$this->assertInstanceOf(\App\Channel::class, $thread->channel);
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
    	$thread = factory(\App\Thread::class)->create();
    	$this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }
}
