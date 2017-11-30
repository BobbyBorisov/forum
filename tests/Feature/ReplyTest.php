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

    	$this->actingAs($user)->post($thread->path().'/replies',[])->assertStatus(422);
    }
}
