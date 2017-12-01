<?php

namespace Tests\Feature;

use App\Notifications\YouWereMentioned;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        Notification::fake();

        $john = factory(\App\User::class)->create(['name' => 'john']);
        $jane = factory(\App\User::class)->create(['name' => 'jane']);

        $this->signIn($john);

        $thread = factory(\App\Thread::class)->create();
        $reply = factory(\App\Reply::class)->make([
            'body' => '@jane look at this @yolo'
        ]);

        $this->postJson($thread->path().'/replies', $reply->toArray());

        Notification::assertSentTo($jane, YouWereMentioned::class);
    }

    /** @test */
    public function no_notification_is_send_when_no_mentioned_users()
    {
        Notification::fake();

        $john = factory(\App\User::class)->create(['name' => 'john']);

        $this->signIn($john);

        $thread = factory(\App\Thread::class)->create();
        $reply = factory(\App\Reply::class)->make([
            'body' => 'send no notification'
        ]);

        $this->postJson($thread->path().'/replies', $reply->toArray());

        Notification::assertNothingSent();
    }
}
