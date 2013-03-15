<?php
/**
 * Created by JetBrains PhpStorm.
 * User: keshav
 * Date: 12/3/13
 * Time: 6:25 PM
 * To change this template use File | Settings | File Templates.
 */

use Zizaco\FactoryMuff\Facade\FactoryMuff;

class UserControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp(); // Don't forget this!
        $this->prepareForTests();
    }


    public function testUserBranches(){
        $branch=$this->getBranch();
        $user=$this->getUser();

        $this->assertEquals(true, true);
    }

    public function testUsertLogin()
    {
        $user = new User();
        $user->email = "keshavashta@yahoo.co.in";
        $user->password = Hash::make("password");

        try {
            $user->save();

        } catch (Exception $e) {
            $this->assertTrue(false);

        }


        $data = array(
            'email' => "keshavashta@yahoo.co.in",
            'password' =>"password"
        );

        //checking for status filter
        $response = $this->action('POST', 'UserController@postLogin', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        $this->assertEquals(true, json_decode($response->getContent())->status);

    }
}