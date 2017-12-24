<?php

namespace Tests\Feature;

use App\Reply;
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
        $replies = factory(Reply::class,2)->create(['thread_id'=> $thread->id]);

        $this->assertFalse($replies[1]->fresh()->isBest());

        $this->postJson(route('best-replies.store',[$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_create_may_mark_reply_as_best()
    {
        $this->signIn();
        $thread = factory(\App\Thread::class)->create(['user_id' => auth()->user()->id]);
        $replies = factory(Reply::class,2)->create(['thread_id'=> $thread->id]);

        $anotherUser = factory(\App\User::class)->create();

        $this->assertFalse($replies[1]->fresh()->isBest());

        $this->actingAs($anotherUser)
             ->postJson(route('best-replies.store',[$replies[1]->id]))
             ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function if_reply_is_deleted_thread_is_updated_to_reflect_that()
    {
    	$user = $this->signIn();

    	$reply = factory(Reply::class)->create(['user_id' => auth()->user()->id]);

    	$reply->thread->markBestReply($reply);

    	$this->assertTrue($reply->fresh()->isBest());
    	$this->assertNotNull($reply->fresh()->thread->best_reply_id);

    	$reply->delete();

        $this->assertFalse($reply->fresh()->isBest());
        $this->assertNull($reply->fresh()->thread->best_reply_id);
    }
}
