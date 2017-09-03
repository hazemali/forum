<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     */

    public function it_has_an_owner()
    {
       $reply =  create('App\Reply');

       $this->assertInstanceOf('App\User' , $reply->owner);
    }


    /** @test * */
    public function a_policy_associated_with_the_reply_creator_after_a_reply_created()
    {

        $this->signIn();
        $reply =  create('App\Reply' , ['user_id' => auth()->id()]);
        $this->assertTrue($reply->isAuthorized(auth()->id()));
    }
}
