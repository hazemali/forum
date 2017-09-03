<?php

namespace Tests\Feature;

use App\Activity;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ActivityTest extends TestCase
{

    use DatabaseMigrations;


    /** @test */
    public function it_record_activity_when_a_thread_created()
    {

        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->assertDatabaseHas('activities', [
            'subject_type' => 'App\Thread',
            'user_id' => auth()->id(),
            'type' => 'created_thread',
            'subject_id' => $thread->id
        ]);

    }

    /** @test * */
    public function it_records_activity_when_a_reply_created()
    {

        $this->signIn();
        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());
    }


    /** @test * */
    public function it_fetches_a_feed_for_any_users()
    {

        $this->signIn();
        create('App\Thread', ['user_id' => auth()->id()]);
        create('App\Thread', [
            'user_id' => auth()->id(),
            'created_at' => carbon::now()->subWeek()
        ]);

        Auth()->user()->activity()->first()->update([
            'created_at' => carbon::now()->subWeek()
        ]);

        $feed = activity::feed(auth()->user(), 50);

        $this->assertTrue(
            $feed->keys()->contains(carbon::now()->format('Y-m-d'))
        );

        $this->assertTrue(
            $feed->keys()->contains(carbon::now()->subWeek()->format('Y-m-d'))
        );

    }

}
