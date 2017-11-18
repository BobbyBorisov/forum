<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $user = factory(\App\User::class)->create();
        $reply = factory(\App\Reply::class)->create();

        $this->actingAs($user)->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->fresh()->favorites);
    }

    /** @test */
    public function guest_cannot_favorite_reply()
    {
        $reply = factory(\App\Reply::class)->create();

        $this->post('/replies/'.$reply->id.'/favorites')->assertRedirect('/login');

        $this->assertCount(0, $reply->fresh()->favorites);
    }

    /** @test */
    public function an_user_cannot_favorite_reply_twice()
    {
        $user = factory(\App\User::class)->create();
        $reply = factory(\App\Reply::class)->create();

        $this->actingAs($user)->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->fresh()->favorites);

        $this->actingAs($user)->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->fresh()->favorites);

    }
}
