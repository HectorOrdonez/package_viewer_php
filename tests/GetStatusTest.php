<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class GetStatusTest extends TestCase
{
    /**
     * @test
     */
    public function index_responds_with_200()
    {
        $this->get('api/status')
            ->assertResponseOk();
    }

    /** @test */
    public function index_responds_with_debconf_when_only_that_entry_exists()
    {
        $response = $this->get('api/status')->shouldReturnJson([
            'debconf',
        ]);
    }

    /** @test */
    public function show_responds_with_error_when_package_does_not_exist() {
        $packageThatDoesNotExist = 'life-universe-and-everything';

        $this->get('api/status/show/' . $packageThatDoesNotExist)->assertResponseStatus(400);
    }

}
