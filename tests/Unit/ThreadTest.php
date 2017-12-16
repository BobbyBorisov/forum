<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
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
        $user = factory(\App\User::class)->create();

        Auth::login($user);

        $thread->addReply([
            'body' => 'Foobar',
            'user_id' => $user->id
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

    /** @test */
    public function user_can_subscribe_to_a_thread()
    {
    	$user = factory(\App\User::class)->create();

    	$thread = factory(\App\Thread::class)->create();

    	$thread->subscribe($user);

    	$this->assertCount(1,$thread->subscriptions()->where('user_id', $user->id)->get());
    }

    /** @test */
    public function user_can_unsubscribe_of_thread()
    {
        $user = factory(\App\User::class)->create();

        $thread = factory(\App\Thread::class)->create();

        $thread->subscribe($user);

        $this->assertCount(1,$thread->subscriptions()->where('user_id', $user->id)->get());

        $thread->unsubscribe($user);

        $this->assertCount(0,$thread->subscriptions()->where('user_id', $user->id)->get());
    }

    /** @test */
    public function it_can_check_if_user_is_subscribed()
    {
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create();

        Auth::login($user);

        $this->assertFalse($thread->isSubscribed());

        $thread->subscribe($user);

        $this->assertTrue($thread->isSubscribed());
    }

    /** @test */
    public function it_knows_how_many_subscribers_it_has()
    {
        $userOne = factory(\App\User::class)->create();
        $userTwo = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create();

        Auth::login($userOne);

        $thread->subscribe($userOne);
        $thread->subscribe($userTwo);

        $this->assertCount(2, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_has_updates_for_user()
    {
    	$user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create();

        $hasUpdatesFor = $thread->hasUpdatesFor($user);

        $this->assertTrue($hasUpdatesFor);
    }

    /** @test */
    public function it_records_each_visit()
    {
        $thread = factory(\App\Thread::class)->create(['id' => 1]);


        $thread->visits()->reset();

        $this->assertSame(0, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());
    }

}
