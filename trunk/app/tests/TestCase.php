<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{

    /**
     * Creates the application.
     *
     * @return Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;
        $testEnvironment = 'testing';
        return require __DIR__ . '/../../bootstrap/start.php';
    }


    protected function prepareForTests()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');
        Mail::pretend(true);
    }

    protected function getUser()
    {

    }

    protected function getEnquiry()
    {
        Enquiry::create(array(
            "name" => "sample name"
        ));
    }
}
