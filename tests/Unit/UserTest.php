<?php
namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{


    use DatabaseMigrations;


    public function setUp()
    {

        parent::setUp();

    }

        /** @test */
    public function it_knows_the_recent_reply()
    {

        $user = create('laravel\User');

        $reply = create('laravel\Reply' , ['user_id' => $user->id]);

        $this->assertEquals($user->lastReply->id , $reply->id);

    }

    /** @test */
    public function it_can_determine_the_avatar_path(){

        $user = create('laravel\User');
        $this->assertEquals($user->avatar_path , asset('images/default-avatar.png'));
        $user->avatar_path = 'avatars/avatar.jpeg';

        $this->assertEquals($user->avatar_path , asset('avatars/avatar.jpeg'));


    }

}