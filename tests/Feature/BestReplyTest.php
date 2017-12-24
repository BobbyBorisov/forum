<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_best()
    {
        $this->signIn();
        $thread = factory(\App\Thread::class)->create(['user_id' => auth()->user()->id]);
        $replies = factory(\App\Reply::class,2)->create(['thread_id'=> $thread->id]);

        $this->assertFalse($replies[1]->fresh()->isBest());

        $this->postJson(route('best-replies.store',[$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_create_may_mark_reply_as_best()
    {
        $this->signIn();
        $thread = factory(\App\Thread::class)->create(['user_id' => auth()->user()->id]);
        $replies = factory(\App\Reply::class,2)->create(['thread_id'=> $thread->id]);

        $anotherUser = factory(\App\User::class)->create();

        $this->assertFalse($replies[1]->fresh()->isBest());

        $this->actingAs($anotherUser)
             ->postJson(route('best-replies.store',[$replies[1]->id]))
             ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
