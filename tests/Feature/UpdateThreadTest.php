<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_modify_thread()
    {
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);

        $this->patchJson($thread->path(),[])->assertStatus(401);
    }

    /** @test */
    public function non_owner_of_thread_cannot_update_it()
    {
        $owner = factory(\App\User::class)->create();
        $nonOwner = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create([
            'user_id' => $owner->id,
            'title' => 'initial title',
            'body' => 'initial body'
        ]);

        $this->actingAs($nonOwner)->patchJson($thread->path(),[
            'title' => 'Changed title',
            'body' => 'Changed body'
        ])->assertStatus(403);

        tap($thread->fresh(), function($thread){
            $this->assertEquals('initial title', $thread->title);
            $this->assertEquals('initial body', $thread->body);
        });
    }

    /** @test */
    public function title_is_required_when_updating_thread()
    {
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);

        $this->patchJson($thread->path(),[
            'title' => null,
            'body' => 'Changed body'
        ])->assertStatus(422);
    }

    /** @test */
    public function body_is_required_when_updating_thread()
    {
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);

        $this->patchJson($thread->path(),[
            'title' => 'Changed title',
            'body' => null
        ])->assertStatus(422);
    }

    /** @test */
    public function owner_of_thread_can_update_it()
    {
        $user = factory(\App\User::class)->create();
        $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);

        $this->patchJson($thread->path(),[
            'title' => 'Changed title',
            'body' => 'Changed body'
        ])->assertStatus(200);

        tap($thread->fresh(), function($thread){
           $this->assertEquals('Changed title', $thread->title);
           $this->assertEquals('Changed body', $thread->body);
        });
    }
}
