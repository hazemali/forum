<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddAvatarTest extends TestCase
{


    use DatabaseMigrations;


    public function setUp()
    {

        parent::setUp();

    }

    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->withExceptionHandling();
        $this->json('post', 'api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provide()
    {

        $this->withExceptionHandling()
            ->signIn();

        $this->json('post', 'api/users/1/avatar', [
            'avatar' => 'not image'
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_may_add_avatar_to_their_profile()
    {

        $this->signIn();

        Storage::fake('public');

        $this->json('post', 'api/users/1/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpeg')
        ]);

        $filePath = 'avatars/' . $file->hashName();

        Storage::disk('public')->assertExists($filePath);

        $this->assertEquals(asset($filePath), Auth()->user()->avatar_path);

    }

}