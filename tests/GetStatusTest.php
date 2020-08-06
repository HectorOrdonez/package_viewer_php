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
}
