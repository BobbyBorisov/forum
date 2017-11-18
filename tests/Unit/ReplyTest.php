<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testItHasAnOwner()
    {
        $reply = factory(Reply::class)->create();
        //dd($reply->owner);
        $this->assertInstanceOf(User::class, $reply->owner);
    }
}
