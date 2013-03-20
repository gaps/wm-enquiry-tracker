<?php

use \Zizaco\FactoryMuff\Facade\FactoryMuff;
class TestCase extends Illuminate\Foundation\Testing\TestCase {

    /**
     * Creates the application.
     *
     * @return Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
    	$unitTesting = true;

        $testEnvironment = 'testing';

    	return require __DIR__.'/../../bootstrap/start.php';
    }
    protected function prepareForTests()
    {
        Artisan::call('migrate');
        Artisan::call('migrate:refresh');
//        Artisan::call('db:seed');
        Mail::pretend(true);
    }

    protected function getUser()
    {
        $user = FactoryMuff::create('User');
        return $user;
    }

    protected function getBranch()
    {
        $branch = FactoryMuff::create('Branch');
        return $branch;
    }

    protected function getEnquiry(){
        $enquiry = FactoryMuff::create('Enquiry');
        return $enquiry;
    }

    protected function getEnquiryStatus(){
        $enquiryStatus = FactoryMuff::create('EnquiryStatus');
        return $enquiryStatus;
    }
}
