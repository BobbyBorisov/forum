<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function administrator_can_lock_thread()
    {
        $this->withoutExceptionHandling();
        $this->signIn(factory(User::class)->states('administrator')->create());

        $thread = factory(Thread::class)->create();
        $this->assertFalse($thread->locked);

        $this->patchJson(route('lock-thread.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function administrator_can_unlock_thread()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());

        $thread = factory(Thread::class)->create(['locked' => true]);
        $this->assertTrue($thread->locked);

        $this->deleteJson(route('lock-thread.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function non_administrator_cannot_lock_thread()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create();
        $this->assertFalse($thread->locked);

        $this->patchJson(route('lock-thread.store', $thread))->assertStatus(302);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    public function non_administrator_cannot_unlock_thread()
    {
        $this->signIn();

        $thread = factory(Thread::class)->create(['locked' => true]);
        $this->assertTrue($thread->locked);

        $this->deleteJson(route('lock-thread.destroy', $thread))->assertStatus(302);

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function a_locked_thread_cannot_receive_more_replies()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create();

        $thread->lock();

        $this->postJson($thread->path().'/replies',[
            'body' => 'Foobar'
        ])->assertStatus(422);
    }
}
