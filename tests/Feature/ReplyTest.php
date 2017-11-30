<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reply_required_a_body()
    {
    	$user = factory(\App\User::class)->create();
    	$thread = factory(\App\Thread::class)->create();

    	$this->actingAs($user)->postJson($thread->path().'/replies',[])->assertStatus(422);
    }

    /** @test */
    public function user_cannot_can_post_replies_once_per_minute()
    {
        //$this->withoutExceptionHandling();
    	$this->signIn();
    	$thread = factory(\App\Thread::class)->create();

    	$this->postJson($thread->path().'/replies',['body' => 'valid body'])
             ->assertStatus(201);

        $this->postJson($thread->path().'/replies',['body' => 'another valid body'])
            ->assertStatus(422);
    }
}
