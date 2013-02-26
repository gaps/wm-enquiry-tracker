<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff;

class ExampleTest extends TestCase
{
    public function setUp()
    {
        parent::setUp(); // Don't forget this!
        $this->prepareForTests();
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h1:contains("Hello World!")'));
    }

    public function testDb()
    {
        $user = FactoryMuff::create('User');

        $user->save();

        $users = User::all();
        $this->assertCount(9, $users);
    }
}