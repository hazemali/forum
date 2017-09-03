<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ProfilesTest extends TestCase
{

    use DatabaseMigrations;

    protected $user;


    public function setUp()
    {

        parent::setUp();

        $this->user = create('App\User');

        $this->signIn($this->user);

    }


    /** @test */
    public function a_user_has_a_profile()
    {

        $this->get("profiles/{$this->user->name}")
            ->assertSee($this->user->name);

    }
    
    /** @test **/
    public function profiles_display_all_threads_created_by_the_associated_user(){


        $thread = create('App\Thread',['user_id' => $this->user->id]);

        $this->get("profiles/{$this->user->name}")
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

}