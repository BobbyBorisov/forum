<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $response = $this->get('/threads');

        $response->assertStatus(200)
                ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_visit_specific_thread()
    {
        $response = $this->get($this->thread->path());

        $response->assertSee($this->thread->title);
    }

    /** @test */
    //TODO make test fetch variables from the view
    //public function a_user_is_able_to_read_replies_that_are_associated_with_the_thread()
    //{
    //    $reply = factory(Reply::class)->create(['thread_id'=> $this->thread->id]);
    //
    //    $this->get($this->thread->path())
    //         ->assertSee($reply->body);
    //}

    /** @test */
    public function a_user_can_filter_threads_according_to_a_tag()
    {
        $channel = factory(\App\Channel::class)->create();
        $thread = factory(\App\Thread::class)->create(['channel_id' => $channel->id]);
        $secondThread = factory(\App\Thread::class)->create();

        $this->get('/threads/'.$channel->slug)
             ->assertSee($thread->title)->assertDontSee($secondThread->title);
    }

    /** @test */
    public function a_user_can_filter_thread_by_username()
    {
        $user = factory(\App\User::class)->create(['name' => 'Bobby']);
    	$threadByBobby = factory(\App\Thread::class)->create(['user_id' => $user->id]);
    	$threadNotByBobby = factory(\App\Thread::class)->create();

    	$this->get('/threads?by=Bobby')
             ->assertSee($threadByBobby->title)
             ->assertDontSee($threadNotByBobby->title);

    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        Thread::destroy(1);

        $threadWithThreeReplies = factory(\App\Thread::class)->create();
        factory(\App\Reply::class,3)->create(['thread_id' => $threadWithThreeReplies->id]);


        $threadWithTwoReplies = factory(\App\Thread::class)->create();
        factory(\App\Reply::class,2)->create(['thread_id' => $threadWithTwoReplies->id]);

        $threadWithOneReply = factory(\App\Thread::class)->create();
        factory(\App\Reply::class,1)->create(['thread_id' => $threadWithOneReply->id]);

        $response = $this->getJson('/threads?popularity')->json();

        //dd($response);

        $this->assertEquals([3,2,1] , array_column($response, 'replies_count'));
    }
}
