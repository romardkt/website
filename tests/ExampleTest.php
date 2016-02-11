<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ExampleTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;
    /**
     * A basic functional test example.
     */
    public function testBasicExample()
    {
        $this->visit('/')
            ->seePageIs('/')
            ->assertResponseOk();
    }
}
