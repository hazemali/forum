<?php

namespace Tests\Feature;

use laravel\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForum extends TestCase
{


    use DatabaseMigrations;


    /**
     * @test
     */

    public function an_unauthenticated_user_may_not_participate_in_forum_thread()
    {


        $this->withExceptionHandling()->post('threads/some-channel/1/replies', [])->assertRedirect('login');


    }

    /**
     * @test
     */

    public function an_authenticated_user_may_participate_in_forum_threads()
    {

        $this->signIn();

        $thread = factory('laravel\Thread')->create();

        $reply = factory('laravel\Reply')->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);

    }


    /** @test * */
    public function a_reply_requires_body()
    {


        $this->withExceptionHandling()->signIn();

        $thread = factory('laravel\Thread')->create();

        $reply = factory('laravel\Reply')->make(['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertSessionHasErrors('body');

    }

    /** @test */
    public function unauthorized_users_can_not_delete_replies()
    {

        $this->withExceptionHandling();

        $reply = factory('laravel\Reply')->create(['user_id' => 999]);

        $this->delete('replies/' . $reply->id)->assertRedirect('login');

        $this->signIn()
            ->delete('replies/' . $reply->id)
            ->assertStatus(403);

    }


    /** @test * */
    public function authorized_users_can_delete_replies()
    {

        $this->withExceptionHandling()->signIn();

        $reply = factory('laravel\Reply')->create(['user_id' => auth()->id()]);

        $this->json('DELETE', 'replies/' . $reply->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);


    }


    /** @test * */
    public function authorized_users_can_update_replies()
    {
        $this->withExceptionHandling()->signIn();

        $reply = factory('laravel\Reply')->create(['user_id' => auth()->id(), 'body' => 'foo']);


        $updatedReply = 'foobar';

        $this->json('PATCH', 'replies/' . $reply->id, [
            'body' => $updatedReply
        ])->assertStatus(204);


        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => '' . $updatedReply . ''
        ]);

    }


    /** @test */
    public function unauthorized_users_can_not_update_replies()
    {

        $this->withExceptionHandling();

        $reply = factory('laravel\Reply')->create(['user_id' => 999, 'body' => 'foo']);

        $updateReply = 'foobar';

        $this->patch('replies/' . $reply->id, [
            'body' => $updateReply
        ])->assertRedirect('login');

        $this->signIn()
            ->json('PATCH', 'replies/' . $reply->id, [
                'body' => $updateReply
            ])->assertStatus(403);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => 'foo'
        ]);


    }


    /** @test */
    public function replies_that_contains_spam_may_not_be_created()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = factory('laravel\Thread')->create();

        $reply = factory('laravel\Reply')->make([
            'body' => 'Yahoo Customer Support'
        ]);


        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);


    }

    /** @test */
    public function users_may_only_reply_a_maximum_once_per_minute()
    {

        $this->withExceptionHandling();

        $this->signIn();

        $thread = factory('laravel\Thread')->create();

        $reply = factory('laravel\Reply')->make([
            'body' => 'My simple reply'
        ]);


        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(201);


        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);

    }
    
    /** @test */
    public function a_thread_records_each_visit(){

        $thread = create('laravel\Thread');

        $this->assertSame(0 , $thread->visits);

        $this->call('GET' , $thread->path());

        $this->assertEquals(1 , $thread->fresh()->visits);


    }


}
