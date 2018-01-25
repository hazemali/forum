<?php

namespace Tests\Unit;

use Carbon\Carbon;
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
        $reply = create('laravel\Reply');

        $this->assertInstanceOf('laravel\User', $reply->owner);
    }


    /** @test */
    public function it_knows_if_was_it_just_published()
    {

        $reply = create('laravel\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());

    }

    /** @test */
    public function it_inspect_the_mentioned_users()
    {


        $reply = new \laravel\Reply([
            'body' => 'hello @zooma and @hazem'
        ]);

        $this->assertEquals(['zooma', 'hazem'], $reply->mentionedUsers());

    }

    /** @test */
    public function it_wraps_mentioned_users_with_anchor_tags()
    {
        $reply = new \laravel\Reply([
            'body' => 'hello @zooma'
        ]);


        $this->assertEquals('hello <a href="/profiles/zooma">@zooma</a>', $reply->body);


    }


    /** @test * */
    public function a_policy_associated_with_the_reply_creator_after_a_reply_created()
    {

        $this->signIn();
        $reply = create('laravel\Reply', ['user_id' => auth()->id()]);
        $this->assertTrue($reply->isAuthorized(auth()->id()));
    }


}
