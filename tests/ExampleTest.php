<?php


class ExampleTest extends TestCase
{
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
