<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
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

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
    	$reply = factory(\App\Reply::class)->create();
    	$this->assertTrue($reply->wasJustPublished());

        $reply = factory(\App\Reply::class)->create(['created_at' => Carbon::now()->subDay()]);
        $this->assertFalse($reply->wasJustPublished());

    }

    /** @test */
    public function it_can_give_mentioned_users_in_the_body()
    {
        $reply = factory(\App\Reply::class)->create([
            'body' => '@yolo and @anotheruser'
        ]);

        $this->assertEquals(['yolo', 'anotheruser'], $reply->mentionedUsers());

        $reply = factory(\App\Reply::class)->create([
            'body' => 'no mentiones'
        ]);

        $this->assertEquals([], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_user_into_anchor_tag()
    {
    	$reply = factory(\App\Reply::class)->make([
    	    'body' => '@JaneDoe, you need to see this'
        ]);

    	$this->assertEquals(
    	    '<a href="/user/JaneDoe/profile">@JaneDoe</a>, you need to see this',
            $reply->body
        );
    }
}
