<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff;

class EnquiryControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp(); // Don't forget this!
        $this->prepareForTests();
    }


    public function testPostAddEnquiry()
    {

        $user = $this->getUser();
        $branch = $this->getBranch();
        $this->be($user);

        $data = array(
            'name' => 'keshav',
            'mobile' => '9891410710',
            'email' => 'email',
            'type' => 'walk in',
            'branch_id' => $branch->id,
            'date' => date("Y-m-d"),
            'program' => 'new program'
        );

        $response = $this->action('POST', 'EnquiryController@postAddEnquiry', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        $this->assertEquals(1, count(Enquiry::all()));
    }


    public function testGetEnquires()
    {
        $firstEnquiryStatus = $this->getEnquiryStatus();
        $secondEnquiryStatus = $this->getEnquiryStatus();

        $firstEnquiryStatus->status = EnquiryStatus::ENROLLED;
        $firstEnquiryStatus->created_at = Util::getFromDate(new DateTime('now'));
        $firstEnquiryStatus->save();


        $firstEnquiry = $firstEnquiryStatus->enquiry()->first();
        $firstEnquiry->enquiryDate = new DateTime('now');
        $firstEnquiry->type = "walk-in";
        $firstEnquiry->save();

        $secondEnquiry = $secondEnquiryStatus->enquiry()->first();
        $secondEnquiry->type = "walk-in";
        $secondEnquiry->save();

        $secondEnquiryStatus->status = EnquiryStatus::NOT_INTERESTED;
        $secondEnquiryStatus->enquiry_id = $firstEnquiryStatus->enquiry_id;
        $secondEnquiryStatus->created_at = new DateTime('now');
        $secondEnquiryStatus->save();
        $data = array(
            'branchIds' => array($firstEnquiry->branch_id),
            'status' => array(EnquiryStatus::ENROLLED, EnquiryStatus::NOT_INTERESTED),
            'types' => array('walk-in'),
            'fromDate' => date('Y-m-d'),
            'toDate' => date('Y-m-d')
        );

        //checking for status filter
        $response = $this->action('POST', 'EnquiryController@postGetEnquiries', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        $this->assertEquals(1, count(json_decode($response->original, true)));
        $this->assertEquals(json_decode($response->original, true)[0]['enquiry_status'][0]['status'], EnquiryStatus::NOT_INTERESTED);

    }

    public function testGetFollowUps()
    {

        $firstEnquiryStatus = $this->getEnquiryStatus();


        $firstEnquiryStatus->status = EnquiryStatus::FOLLOW_UP;
        $firstEnquiryStatus->followupDate = Util::getFromDate(new DateTime('now'));
        $firstEnquiryStatus->save();


        $firstEnquiry = $firstEnquiryStatus->enquiry()->first();
        $firstEnquiry->type = "walk-in";
        $firstEnquiry->save();


        $data = array(
            'branchIds' => array($firstEnquiry->branch_id),
            'types' => array('walk-in'),
            'fromDate' => date('Y-m-d'),
            'toDate' => date('Y-m-d')
        );

        //checking for followup size
        $response = $this->action('POST', 'EnquiryController@postGetFollowUps', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        $this->assertEquals(1, count(json_decode($response->original, true)));


        //second test
        $firstEnquiryStatus->status = EnquiryStatus::ENROLLED;
        $firstEnquiryStatus->followupDate = Util::getFromDate(new DateTime('now'));
        $firstEnquiryStatus->save();


        $firstEnquiry = $firstEnquiryStatus->enquiry()->first();
        $firstEnquiry->type = "walk-in";
        $firstEnquiry->save();


        $data = array(
            'branchIds' => array($firstEnquiry->branch_id),
            'types' => array('walk-in'),
            'fromDate' => date('Y-m-d'),
            'toDate' => date('Y-m-d')
        );

        //checking for status filter
        $response = $this->action('POST', 'EnquiryController@postGetFollowUps', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        // Todo ask sir for empty response
        $this->markTestIncomplete(
            'check for empty response'
        );

    }


    public function testMarkEnrolled()
    {

        $enquiry = FactoryMuff::create('Enquiry');
        $enquiryStatus = FactoryMuff::create('EnquiryStatus');
        $enquiryStatus->status = EnquiryStatus::FOLLOW_UP;
        $enquiryStatus->save();
        $data = array(
            'enquiryId' => $enquiry->id,
            'joiningDate' => date('Y-m-d'),
            'remarks' => "test"
        );

        //checking for enquiry status size and enquiry status is enrolled
        $response = $this->action('POST', 'EnquiryController@postMarkEnrolled', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        $this->assertEquals(1, count(json_decode($response->original)));
        $this->assertEquals(json_decode($response->original, true)['status'], EnquiryStatus::ENROLLED);

    }

    public function testMarkEnquiryNew()
    {

        $enquiry = FactoryMuff::create('Enquiry');
        $enquiryStatus = FactoryMuff::create('EnquiryStatus');
        $enquiryStatus->status = EnquiryStatus::FOLLOW_UP;
        $enquiryStatus->save();
        $data = array(
            'enquiryId' => $enquiry->id
        );

        //checking for enquiry status size and enquiry status is enrolled
        $response = $this->action('POST', 'EnquiryController@postMarkEnquiryNew', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        $this->assertEquals(1, count(json_decode($response->original)));
        $this->assertEquals(json_decode($response->original, true)['status'], EnquiryStatus::CREATED);

    }


    public function testMarkEnquiryNotInterested()
    {

        $enquiry = FactoryMuff::create('Enquiry');
        $enquiryStatus = FactoryMuff::create('EnquiryStatus');
        $enquiryStatus->status = EnquiryStatus::FOLLOW_UP;
        $enquiryStatus->save();
        $data = array(
            'enquiryId' => $enquiry->id,
            'remarks' => "test"
        );

        //checking for enquiry status size and enquiry status is enrolled
        $response = $this->action('POST', 'EnquiryController@postMarkEnquiryNotInterested', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        $this->assertEquals(1, count(json_decode($response->original)));
        $this->assertEquals(json_decode($response->original, true)['status'], EnquiryStatus::NOT_INTERESTED);

    }

    public function testCreateFollowup()
    {

        $enquiry = FactoryMuff::create('Enquiry');
        $enquiryStatus = FactoryMuff::create('EnquiryStatus');
        $enquiryStatus->status = EnquiryStatus::ENROLLED;
        $enquiryStatus->save();
        $data = array(
            'enquiryId' => $enquiry->id,
            'remarks' => "test",
            'followupDate' => date('Y-m-d')
        );

        //checking for enquiry status size and enquiry status is enrolled
        $response = $this->action('POST', 'EnquiryController@postCreateFollowup', array(), array(), array(), array(), json_encode($data));
        $this->assertTrue($response->isOk());
        $this->assertEquals(1, count(json_decode($response->original)));
        $this->assertEquals(json_decode($response->original, true)['status'], EnquiryStatus::FOLLOW_UP);

    }


}