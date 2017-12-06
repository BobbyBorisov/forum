<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Mail\ConfirmAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_confirmation_code_when_user_registers()
    {
        $this->post('/register',[
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $user = User::first();
        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);
    }

    /** @test */
    public function email_is_send_when_user_registers()
    {
        Mail::fake();

        $this->post('/register',[
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        Mail::assertQueued(ConfirmAccount::class);
    }

    /** @test */
    public function user_is_able_to_fully_confirm_his_account()
    {
        $this->withoutExceptionHandling();
    	$user = factory(User::class)->states('unconfirmed')->create([
    	    'confirmation_token' => 'RANDOM_TOKEN'
        ]);

        $request = $this->get('/api/users/confirm?confirmation_token='.$user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);
        $this->assertNull($user->fresh()->confirmation_token);
        $request->assertRedirect('/threads');
    }
}