<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ReadThreadsTest extends TestCase
{

    use DatabaseMigrations;


    public $thread;

    public function setUp()
    {

        parent::setUp();
        $this->thread = create('App\Thread');

    }

    /**
     * @test
     */
    public function a_user_can_browse_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);


    }

    /**
     * @test
     */

    public function a_user_can_read_single_thread()
    {

        $this->get($this->thread->path())
            ->assertSee($this->thread->title);


    }


    /** @test * */
    public function a_user_can_filter_threads_according_to_a_channel()
    {

        $channel = create('App\Channel');

        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);

        $threadNotInChannel = create('App\Thread');

        $this->get("threads/{$channel->slug}")
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test * */
    public function a_user_can_filter_threads_by_any_name()
    {

        $this->signIn(create('App\User', ['name' => 'JohnDoe']));
        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);

    }

    /** @test * */
    public function a_user_can_filter_threads_by_popularity()
    {


        $threadWithTowReplies = create('App\Thread');

        create('App\Reply', ['thread_id' => $threadWithTowReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread');


        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;


        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));

    }


    /** @test **/
    public function a_user_can_filter_thread_by_those_that_are_unanswered()
    {


        $threadWithReplies = create('App\Thread');

        create('App\Reply', ['thread_id' => $threadWithReplies->id]);


        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response);


    }


    /** @test * */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {


        $thread = $this->thread;

        create('App\Reply', ['thread_id' => $thread->id], 2);
        $response = $this->getJson($thread->path() . '/replies')->json();


        $this->assertEquals(2, $response['total']);

    }


}
