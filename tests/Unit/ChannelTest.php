<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChannelTest extends TestCase
{

    use DatabaseMigrations;
 
    
    /** @test **/
    public function it_consists_of_threads(){

        $channel = create('laravel\Channel');

        $thread = create('laravel\Thread',['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
    
}
