<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class FavoritesTest extends TestCase
{

    use DatabaseMigrations;


    /** @test **/
    public function guests_can_not_favorite_any_reply(){

        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('login');

    }

    /** @test */
    public function an_authenticated_user_can_un_favorite_any_reply()
    {

        $this->signIn();

        $reply = create('laravel\Reply');

        $reply->favorite();

        $this->delete('replies/'.$reply->id.'/favorites');

        $this->assertCount(0,$reply->favorites);

    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {

        $this->signIn();

        $reply = create('laravel\Reply');

        $this->post('replies/'.$reply->id.'/favorites');

        $this->assertCount(1,$reply->favorites);
        
    }

    /** @test **/
    public function an_authenticated_user_may_only_favorite_a_reply_once(){

        $this->signIn();

        $reply = create('laravel\Reply');

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        }catch (\Exception $e){
            $this->fail('Did not expect to insert the same recode set twice.');
        }
        $this->assertCount(1,$reply->favorites);
    }

}
