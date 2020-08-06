<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class GetStatusTest extends TestCase
{
    /**
     * @test
     */
    public function it_responds_with_200_when_requesting_all_status_content()
    {
        $this->get('api/status')
            ->assertResponseOk();
    }

    /** @test */
    public function it_responds_with_debconf_in_contents_when_status_only_has_that_entry()
    {
        $response = $this->get('api/status')->shouldReturnJson([
            'debconf',
        ]);
    }

}
