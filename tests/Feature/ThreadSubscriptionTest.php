<?php

namespace Tests\Feature;

use App\Notifications\NewReplyAdded;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_subscribe_to_a_thread()
    {
        $thread = factory(\App\Thread::class)->create();

        $this->postJson($thread->path().'/subscriptions',[])
             ->assertStatus(401);
    }

    /** @test */
    public function user_receives_notification_when_reply_is_left_on_subscribed_thread()
    {
        Notification::fake();

        $user = factory(\App\User::class)->create(['name' => 'user']);
        $johnDoe = factory(\App\User::class)->create(['name' => 'johndoe']);

        $thread = factory(\App\Thread::class)->create();

        $this->actingAs($user)->postJson($thread->path().'/subscriptions',[]);

        Auth::login($johnDoe);

        $thread->addReply(['body'=>'reply baby', 'user_id' => $johnDoe->id]);

        Notification::assertSentTo($user, ThreadWasUpdated::class);
    }

}
