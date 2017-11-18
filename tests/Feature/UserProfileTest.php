<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_profile_can_be_viewed_by_others()
    {
        $this->withoutExceptionHandling();

        $user = factory(\App\User::class)->create(['name' => 'Bob Marley']);

        Auth::login($user);

        $threadByBob = factory(\App\Thread::class)->create(['user_id' => $user]);
        $threadNotByThisUser = factory(\App\Thread::class)->create();

        $request = $this->actingAs($user)->get("/user/{$user->id}/profile");

        $request->assertSee($threadByBob->title);
        //$request->assertDontSee($threadNotByThisUser->title);
    }

    /** @test */
    public function user_profile_threads_are_orders_by_latest_created_date()
    {
        $this->withoutExceptionHandling();
        $user = factory(\App\User::class)->create(['name' => 'Bob Marley']);

        Auth::login($user);

        $threadOne = factory(\App\Thread::class)->create(['user_id' => $user->id, 'created_at' => Carbon::now()->subDays(3)]);
        $replyOne = factory(\App\Reply::class)->create(['user_id' => $user->id, 'thread_id' => $threadOne, 'created_at' => Carbon::now()->subDays(3)]);

        $threadTwo = factory(\App\Thread::class)->create(['user_id' => $user->id,'created_at' => Carbon::now()->subDays(2)]);
        $threadThree = factory(\App\Thread::class)->create(['user_id' => $user->id,'created_at' => Carbon::now()->subDays(1)]);
        //dd($threadThree->toArray());

        $replyThree = factory(\App\Reply::class)->create(['user_id' => $user->id, 'thread_id' => $threadThree, 'created_at' => Carbon::now()]);

        $response = $this->actingAs($user)->getJson("/user/{$user->id}/profile")->json();

       // dd($response);
        //$this->assertEquals([3,2,1] , array_column($response, 'id'));
        $this->assertArraySubset(
        [
            Carbon::now()->format('Y-m-d') => [
                [
                    'type' => 'created_reply',
                    'subject_id'   => $replyThree->id
                ]
            ],
            Carbon::now()->subDays(1)->format('Y-m-d') => [
                [
                    'type' => 'created_thread',
                    'subject_id'   => $threadThree->id
                ]
            ],
            Carbon::now()->subDays(2)->format('Y-m-d') => [
                [
                    'type' => 'created_thread',
                    'subject_id'   => $threadTwo->id
                ]
            ],
            Carbon::now()->subDays(3)->format('Y-m-d') => [
                [
                    'type' => 'created_thread',
                    'subject_id'   => $threadOne->id
                ],
                [
                    'type' => 'created_reply',
                    'subject_id'   => $replyOne->id
                ]
            ],
        ],$response);
    }
}
