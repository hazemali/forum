<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUserTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {

        $john = create('laravel\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create('laravel\User', ['name' => 'janeDoe']);

        $thread = create('laravel\Thread');

        $reply = make('laravel\Reply', [
            'body' => '@janeDoe look at this.'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1 , $jane->fresh()->notifications);

    }


    /** @test */
    public function it_can_fetch_all_the_mentioned_users_starting_with_the_given_chacaters(){


        create('laravel\User',[
            'name' => 'johndoe'
        ]);
        create('laravel\User',[
            'name' => 'johndoe2'
        ]);

        create('laravel\User',[
            'name' => 'jahendoe'
        ]);

        $response = $this->json('GET','api/users' , ['name' => 'john']);

        $this->assertCount(2 , $response->json());

    }
}
