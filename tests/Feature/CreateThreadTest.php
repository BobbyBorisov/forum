<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_is_able_to_create_thread()
    {
        $this->withoutExceptionHandling();
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->make();

        $this->actingAs($user)->post('/threads', $thread->toArray());

        $this->get('/threads')
             ->assertSee($thread->body);
    }

    /** @test */
    public function guest_is_not_able_to_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->withoutExceptionHandling();

        $this->post('/threads', []);
    }

    /** @test */
    public function guest_cannot_see_create_thread_page()
    {
    	$this->get('/threads/create')
             ->assertRedirect('/login');
    }

    /** @test */
    public function title_is_required_to_create_a_thread()
    {
    	$user = factory(\App\User::class)->create();

    	$this->actingAs($user)->post('/threads',[
    	    'body' => 'somebody'
        ])->assertSessionHasErrors('title');
    }


    /** @test */
    public function body_is_required_to_create_a_thread()
    {
        $user = factory(\App\User::class)->create();

        $this->actingAs($user)->post('/threads',[
            'title' => 'sometitle'
        ])->assertSessionHasErrors('body');
    }


    /** @test */
    public function channel_is_required_to_create_a_thread()
    {
        $user = factory(\App\User::class)->create();

        $this->actingAs($user)->post('/threads',[
            'title' => 'sometitle',
            'body'  => 'somebody'
        ])->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function channel_must_exist_in_database()
    {
        $user = factory(\App\User::class)->create();

        $this->actingAs($user)->post('/threads',[
            'title' => 'sometitle',
            'body'  => 'somebody',
            'channel_id' => 99999
        ])->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_creator_of_thread_can_delete_it()
    {
        $user = factory(\App\User::class)->create();
        Auth::login($user);
    	$thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);
    	$replies = factory(\App\Reply::class,3)->create(['thread_id' => $thread->id]);

    	$this->actingAs($user)->delete("/threads/{$thread->id}");

    	$this->assertCount(0, \App\Thread::all());
        $this->assertCount(0, \App\Reply::all());
        $this->assertCount(0, \App\Activity::all());
    }

    /** @test */
    public function authorized_user_but_not_creator_cannot_delete_thread()
    {
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create();
        $replies = factory(\App\Reply::class,3)->create(['thread_id' => $thread->id]);

        $this->actingAs($user)->delete("/threads/{$thread->id}");

        $this->assertCount(1, \App\Thread::all());
        $this->assertCount(3, \App\Reply::all());
    }

    /** @test */
    public function guest_cannot_delete_thread()
    {
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create();

        factory(\App\Reply::class,3)->create(['thread_id' => $thread->id]);

        $this->actingAs($user)->delete("/threads/{$thread->id}");

        $this->assertCount(1, \App\Thread::all());
        $this->assertCount(3, \App\Reply::all());
    }
}
