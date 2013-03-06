<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff;

class EnquiryControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp(); // Don't forget this!
        $this->prepareForTests();
    }

    public function testSample()
    {
        $this->assertTrue(true);
    }

    public function testPostAddEnquiry()
    {
        $enquiry = FactoryMuff::create('Enquiry');

        Input::$json = $enquiry;

        $response = $this->action('POST', 'EnquiryController@postAddEnquiry');
        $this->assertTrue($response->isOk());
    }


}