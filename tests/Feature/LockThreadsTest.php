<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_may_lock_any_thread()
    {
        $this->signIn();
        $thread = factory(Thread::class)->create();

        $thread->lock();

        $this->postJson($thread->path().'/replies',[
            'body' => 'Foobar'
        ])->assertStatus(422);
    }

}
