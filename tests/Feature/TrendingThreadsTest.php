<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Redis;
use laravel\Trending;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrendingThreadsTest extends TestCase
{


    protected $trending;

    use DatabaseMigrations;


    public function setUp()
    {

        parent::setUp();


        $this->trending = new Trending();
        $this->trending->reset();

    }

    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {

        $this->assertEmpty($this->trending->get());

        $thread = create('laravel\Thread');

        $this->call('GET', $thread->path());

        $this->assertCount(1, $trending = $this->trending->get());
        $this->assertEquals($thread->title, $trending[0]->title);


    }


}