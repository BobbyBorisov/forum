<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_knows_his_last_reply()
    {
        $user = factory(\App\User::class)->create();
    	$this->signIn($user);

    	$this->assertNull($user->lastReply);

    	$reply = factory(\App\Reply::class)->create(['user_id' => $user->id]);

    	$this->assertEquals($reply->id, $user->fresh()->lastReply->id);
    }
}
