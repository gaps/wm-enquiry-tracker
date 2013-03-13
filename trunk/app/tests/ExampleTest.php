<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff;

class ExampleTest extends TestCase
{
    public function setUp()
    {
        parent::setUp(); // Don't forget this!
        $this->prepareForTests();
    }

    public function testDb()
    {
        $user = FactoryMuff::create('User');

        $user->save();

        $users = User::all();
        $this->assertCount(1, $users);
    }
}