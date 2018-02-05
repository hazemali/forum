<?php

namespace Tests\Feature;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use laravel\Mail\PleasConfirmYourEmail;
use laravel\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{


    use DatabaseMigrations;


    public function setUp()
    {

        parent::setUp();

    }

    /** @test */
    public function a_confirmation_email_sent_upon_registration()
    {

        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@site.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);

        Mail::assertSent(PleasConfirmYourEmail::class);

    }


    /** @test */
    public function users_can_fully_confirm_their_email_addresses()
    {

        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@site.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);


        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->fresh()->confirmation_token);

        $this->get(route('register.confirm',
            ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads.index'));

        $this->assertTrue($user->fresh()->confirmed);


    }

    /** @test */
    public function confirm_an_invalid_token()
    {

        $this->get(route('register.confirm',
            ['token' => 'invalid']))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flashError');

    }

}