<?php

namespace Tests\Unit;

use laravel\inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{

    protected $spam;

    public function setUp()
    {
        parent::setUp();

        $this->spam = new Spam();

    }

    /** @test */
    public function it_checks_for_invalid_keywords()
    {

        $this->assertFalse($this->spam->detect('innocent reply'));

        $this->expectException(\Exception::class);

        $this->spam->detect('yahoo customer support');


    }

    /** @test */
    public function it_checks_for_any_key_being_held_down()
    {

        $this->expectException(\Exception::class);

        $this->spam->detect('hello world aaaaa');


    }


}