<?php

namespace Tests\Unit;

use App\Activity;
use Carbon\Carbon;
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

    /** @test */
    public function it_can_returns_activity_feed()
    {
        $user = factory(\App\User::class)->create();
        Auth::login($user);
        $thread = factory(\App\Thread::class)->create(['user_id'=>$user->id]);
        $reply = factory(\App\Reply::class)->create(['thread_id'=>$thread->id]);

        $firstActivity = Activity::first()
                                  ->update(['created_at' => Carbon::now()->subWeek()]);

        $acvitities = Activity::feed();

        $this->assertCount(2, $acvitities);

        //dd($acvitities->toArray());
        //
        //$this->assertArraySubset([
        //    Carbon::now()->format('Y-m-d') => [
        //        ['type' => 'created_thread']
        //    ]
        //],$acvitities);
    }
}
