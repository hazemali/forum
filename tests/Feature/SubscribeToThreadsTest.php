<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class SubscribeToThreadsTest extends TestCase
{

    use DatabaseMigrations;


    /** @test */

    public function an_authenticated_user_can_subscribe_to_threads()
    {

        $thread = create('App\Thread');

        $this->signIn();

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->subscriptions);




    }


    /** @test */

    public function an_unauthenticated_user_can_not_subscribe_to_threads()
    {

        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions')
            ->assertRedirect('login');

    }


    /** @test * */
    public function an_authenticated_user_can_unsubscribe_a_subscribed_thread()
    {


        $thread = create('App\Thread');

        $this->signIn();

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);


    }


}
