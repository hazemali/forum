<?php

namespace Tests\Feature;

use App\Reply;
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

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies',['body' => $reply->body]);
        $this->assertEquals(1,$thread->fresh()->replies_count);

    }


    /** @test * */
    public function a_reply_requires_body()
    {


        $this->withExceptionHandling()->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make(['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertSessionHasErrors('body');

    }

    /** @test */
    public function unauthorized_users_can_not_delete_replies()
    {

        $this->withExceptionHandling();

        $reply = factory('App\Reply')->create(['user_id' => 999]);

        $this->delete('replies/' . $reply->id)->assertRedirect('login');

        $this->signIn()
            ->delete('replies/' . $reply->id)
            ->assertStatus(403);

    }


    /** @test * */
    public function authorized_users_can_delete_replies()
    {

        $this->withExceptionHandling()->signIn();

        $reply = factory('App\Reply')->create(['user_id' => auth()->id()]);

        $this->json('DELETE', 'replies/' . $reply->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);

        $this->assertEquals(0 , $reply->thread->fresh()->replies_count);


    }


    /** @test * */
    public function authorized_users_can_update_replies()
    {
        $this->withExceptionHandling()->signIn();

        $reply = factory('App\Reply')->create(['user_id' => auth()->id(), 'body' => 'foo']);


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

        $reply = factory('App\Reply')->create(['user_id' => 999, 'body' => 'foo']);

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


}
