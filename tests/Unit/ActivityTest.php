<?php

namespace Tests\Unit;

use App\Activity;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function activity_is_recorded_when_thread_is_created()
    {

        $user = factory(\App\User::class)->create();
        Auth::login($user);
    	$thread = factory(\App\Thread::class)->create();

        $activity = Activity::first();

        $this->assertEquals('created_thread', $activity->type);
        $this->assertEquals($thread->id, $activity->subject_id);
        $this->assertEquals('App\Thread', $activity->subject_type);
        $this->assertEquals($user->id, $activity->user_id);
    }
}
