<?php

namespace Tests\Feature;

use laravel\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{


    use DatabaseMigrations;


    /**
     * @test
     */
    public function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get(route('threads.create'))->assertRedirect(route('login'));
        $this->post(route('threads.store'), [])->assertRedirect(route('login'));

    }


    /** @test */
    public function new_users_must_confirm_their_email_address_before_creating_threads()
    {


        $user = factory('laravel\User')->states('unconfirmed')->create();
        $this->signIn($user);

        $thread = make('laravel\Thread');

        $this->post('threads', $thread->toArray())
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flashError');
    }

    /**
     * @test
     */
    public function a_user_can_create_new_forum_threads()
    {

        $this->signIn();

        $thread = make('laravel\Thread');

        $response = $this->post(route('threads.store'), $thread->toArray());

        $this->get($response->headers->get('Location'))->assertSee($thread->title)
            ->assertSee($thread->body);
    }


    /** @test * */
    public function a_thread_make_a_string_path()
    {

        $thread = create('laravel\Thread');

        $this->assertEquals(route('threads.show', [$thread->channel->slug, $thread->id]), $thread->path());
    }

    /** @test * */
    public function a_thread_requires_a_title()
    {

        $this->publishThread(['title' => null])->assertSessionHasErrors('title');
    }


    /** @test * */
    public function a_thread_requires_a_body()
    {

        $this->publishThread(['body' => null])->assertSessionHasErrors('body');
    }


    /** @test * */
    public function a_thread_requires_a_valid_channel_id()
    {

        create('laravel\Channel');


        $this->publishThread(['channel_id' => null])->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])->assertSessionHasErrors('channel_id');
    }


    /**
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */

    public function publishThread($overrides = [])
    {

        $this->withExceptionHandling()->signIn();

        $thread = make('laravel\Thread', $overrides);

        return $this->post(route('threads.store'), $thread->toArray());

    }


    /** @test * */
    public function unauthorized_users_may_not_delete_threads()
    {

        $thread = create('laravel\Thread', ['user_id' => 9999]);

        $this->withExceptionHandling();

        $this->delete($thread->path())
            ->assertRedirect('login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);


    }


    /** @test * */
    public function threads_may_only_deleted_by_those_who_have_permissions()
    {

        // trying to delete the thread with the creator user which is authorized
        $this->signIn();

        $thread = create('laravel\Thread', ['user_id' => auth()->id()]);
        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);
        auth()->logout();


        // trying to delete the thread with another authorized user
        $this->signIn();
        $thread = create('laravel\Thread', ['user_id' => 9999]);

        $thread->AuthorizeUser(auth()->id());
        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);
        auth()->logout();


    }


    /** @test * */
    public function a_thread_delete_with_all_of_his_associated_resources()
    {
        $this->signIn();

        $thread = create('laravel\Thread', ['user_id' => auth()->id()]);


        $reply = create('laravel\Reply', ['thread_id' => $thread->id]);

        $favorite = $reply->favorite();

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        //$this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('favorites', ['id' => $favorite->id]);

        $this->assertEquals(0, Activity::count());

    }
}
