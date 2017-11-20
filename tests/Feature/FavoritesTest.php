<?php

namespace Tests\Feature;

use App\Reply;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->create();

        Auth::login($user);
        $this->actingAs($user)->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->fresh()->favorites);
    }

    /** @test */
    public function guest_cannot_favorite_reply()
    {
        $reply = factory(Reply::class)->create();

        $this->post('/replies/'.$reply->id.'/favorites')->assertRedirect('/login');

        $this->assertCount(0, $reply->fresh()->favorites);
    }

    /** @test */
    public function an_user_cannot_favorite_reply_twice()
    {
        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->create();

        $this->actingAs($user)->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->fresh()->favorites);

        $this->actingAs($user)->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->fresh()->favorites);
    }
}
