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

    /** @test */
    public function it_can_determine_his_avatar_path()
    {
        $user = factory(\App\User::class)->create();

        $this->assertEquals(asset('images/avatars/default.png'), $user->avatar_path);

        $user->update(['avatar_path' => 'images/avatars/me.jpg']);

        $this->assertEquals(asset('images/avatars/me.jpg'), $user->fresh()->avatar_path);
    }
}
