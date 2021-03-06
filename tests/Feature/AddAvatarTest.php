<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function only_members_can_add_avatar()
    {
        $this->postJson('/api/users/1/avatar')
             ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
    	$this->signIn();

        $this->postJson('/api/users/'.auth()->user()->id.'/avatar',[
            'avatar' => 'not-an-imaage'
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_can_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->postJson('/api/users/'.auth()->user()->id.'/avatar',[
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('avatars/'.$file->hashName()), auth()->user()->avatar_path);
        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }
}
