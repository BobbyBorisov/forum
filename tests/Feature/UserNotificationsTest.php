<?php

namespace Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserNotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authorized_user_can_fetch_his_notifications()
    {
        $user = factory(\App\User::class)->create();

        factory(DatabaseNotification::class)->create(['notifiable_id' => $user->id]);

        $response = $this->actingAs($user)
             ->getJson('/profile/'.$user->id.'/notifications',[])
             ->assertStatus(200);

        $this->assertCount(1,$response->original);
    }

    /** @test */
    public function user_can_mark_his_notification_as_read()
    {
    	$user = factory(\App\User::class)->create();

        $notification = factory(DatabaseNotification::class)->create(['notifiable_id' => $user->id]);

        $this->assertCount(1,$user->fresh()->unreadNotifications);

        $this->actingAs($user)
             ->postJson('/profile/'.$user->id.'/notifications/'.$notification->id)
             ->assertStatus(204);

        $this->assertCount(0,$user->fresh()->unreadNotifications);
    }
}
