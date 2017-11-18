<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->withoutExceptionHandling();
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create();
        $reply = factory(\App\Reply::class)->make();


        $this->actingAs($user)->post($thread->path().'/replies', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function a_guest_cannot_post_a_reply()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->withoutExceptionHandling();

        $this->post('/threads/some/1/replies',[]);
    }
}
