<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
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
    //public function an_authenticated_user_may_participate_in_forum_threads()
    //{
    //    $this->withoutExceptionHandling();
    //    $user = factory(\App\User::class)->create();
    //    $thread = factory(\App\Thread::class)->create();
    //    $reply = factory(\App\Reply::class)->make();
    //
    //
    //    $this->actingAs($user)->post($thread->path().'/replies', $reply->toArray());
    //
    //    $this->get($thread->path())
    //         ->assertSee($reply->body);
    //}

    /** @test */
    public function a_guest_cannot_post_a_reply()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->withoutExceptionHandling();

        $this->post('/threads/some/1/replies',[]);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        //$this->withoutExceptionHandling();
        $thread = factory(\App\Thread::class)->create();
        $reply = factory(\App\Reply::class)->create(['thread_id' => $thread->id]);

        $this->delete($thread->path().'/replies/'.$reply->id)
             ->assertRedirect('/login');
    }

    /** @test */
    public function authorized_users_may_delete_their_own_replies()
    {
        $this->withoutExceptionHandling();
        $user = factory(\App\User::class)->create();
        Auth::login($user);

        $thread = factory(\App\Thread::class)->create();
        $reply = factory(\App\Reply::class)->create(['thread_id' => $thread->id, 'user_id' => $user->id]);

        $this->actingAs($user)->delete($thread->path().'/replies/'.$reply->id);

        $this->assertCount(0, $thread->replies);

        $this->assertDatabaseMissing('activities',[
            'type' => 'created_reply',
            'subject_id' => $reply->id,
            'subject_type' => 'App\Reply',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function when_owner_of_reply_deletes_it_he_deletes_all_activity_and_favorites_with_it()
    {
        $user = factory(\App\User::class)->create();
        Auth::login($user);

        $thread = factory(\App\Thread::class)->create();
        $reply = factory(\App\Reply::class)->create(['thread_id' => $thread->id, 'user_id' => $user->id]);
        $reply->favorite();

        $this->delete($thread->path().'/replies/'.$reply->id);

        $this->assertCount(0, $thread->replies);

        $this->assertDatabaseMissing('activities',[
            'type' => 'created_reply',
            'subject_id' => $reply->id,
            'subject_type' => 'App\Reply',
            'user_id' => $user->id
        ]);

        $this->assertCount(0, \App\Favorite::all());
    }

    /** @test */
    public function authorized_users_cannot_delete_other_users_replies()
    {
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create();
        $reply = factory(\App\Reply::class)->create(['thread_id' => $thread->id]);

        $this->actingAs($user)->delete($thread->path().'/replies/'.$reply->id);

        $this->assertCount(1, $thread->replies);
    }

    /** @test */
    public function authorized_users_can_update_reply()
    {
    	$user = factory(\App\User::class)->create();
    	$reply = factory(\App\Reply::class)->create();

    	$this->patch('/replies/'.$reply->id,[
    	    'body' => 'new body'
        ]);

    	$this->assertEquals('new body', \App\Reply::first()->body);
    }
}
