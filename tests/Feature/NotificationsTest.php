<?php

namespace Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class NotificationsTest extends TestCase
{

    use DatabaseMigrations;


    public function setUp()
    {

        parent::setUp();

        $this->signIn();

    }


    /** @test */

    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {


        $thread = create('laravel\Thread')->subscribe();


        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);


        $this->assertCount(0, auth()->user()->notifications);


        $thread->addReply([
            'user_id' => create('laravel\User')->id,
            'body' => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);


    }


    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {


        create(DatabaseNotification::class);


        tap(auth()->user(), function ($user) {

            $this->assertCount(1, $user->unreadnotifications);

            $this->delete("notifications/". $user->unreadnotifications()->first()->id);

            $this->assertCount(0, $user->fresh()->unreadnotifications);


        });

    }


    /** @test * */
    public function a_user_can_fetch_their_unread_notifications()
    {


        create(DatabaseNotification::class);

        $response = $this->getJson("notifications")->json();
        $this->assertCount(1, $response);
    }

}
