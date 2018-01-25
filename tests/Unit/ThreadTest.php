<?php

namespace Tests\Unit;

use laravel\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadTest extends TestCase
{

    public $thread;

    use DatabaseMigrations;


    public function setUp()
    {

        parent::setUp();

        $this->signIn();


        $this->thread = create('laravel\Thread', ['user_id' => auth()->id()]);


    }

    /**
     * @test
     */

    public function a_thread_has_creator()
    {


        $this->assertInstanceOf('laravel\User', $this->thread->creator);

    }


    /** @test */

    public function a_thread_has_replies()
    {

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);

    }


    /**
     * @test
     */
    public function a_thread_can_add_a_reply()
    {


        $this->thread->addReply([
            'body' => 'foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);


    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {

        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'foobar',
                'user_id' => 999
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);


    }
    
    


    /** @test **/
    public function a_thread_belongs_to_channel()
    {

        $this->assertInstanceOf('laravel\Channel', $this->thread->channel);

    }


    /** @test **/
    public function a_policy_associated_with_the_thread_creator_after_thread_created()
    {
        $this->assertTrue($this->thread->isAuthorized());
    }


    /** @test **/
    public function a_thread_can_be_subscribe_to()
    {

        $thread = create('laravel\Thread');

        $thread->subscribe($userId = 1);


        $this->assertEquals(
            1,
            $thread->subscriptions()->where(['user_id' => $userId])->count()
        );

    }


    /** @test **/
    public function a_thread_can_unsubscribed_from()
    {

        $thread = create('laravel\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(
            0,
            $thread->fresh()->subscriptions
        );

    }


    /** @test **/
    public function it_knows_if_the_authenticated_user_is_subscribe_to_it()
    {

        $this->signIn();

        $thread = create('laravel\Thread');

        $this->assertFalse($thread->isSubscribed);

        $thread->subscribe();


        $this->assertTrue($thread->fresh()->isSubscribed);


    }

    /** @test */
    public function a_thread_can_check_if_an_authenticated_user_has_read_all_replies()
    {

        $this->signIn();

        $thread = create('laravel\Thread');

        tap(auth()->user() , function ($user)  use ($thread){
            $this->assertTrue($thread->hasUpdatesFor());

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor());
        });


    }



}
