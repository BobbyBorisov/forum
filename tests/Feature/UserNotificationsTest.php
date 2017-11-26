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
        $this->withoutExceptionHandling();
        $user = factory(\App\User::class)->create();

        factory(DatabaseNotification::class)->create(['notifiable_id' => $user->id]);

        $response = $this->actingAs($user)
             ->getJson('/profile/'.$user->id.'/notifications',[])
             ->assertStatus(200);

        $this->assertCount(1,$response->original);
    }
}
