<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Usage\RejectResponse;

/**
 * Class RejectResponseTest
 * @package Tests\Unit
 */
class RejectResponseTest extends \Test\TestCase
{

    /**
     * @return RejectResponse
     */
    public function testInstantiation()
    {
        $responseReject = new RejectResponse('Test');

        $this->assertInstanceOf('\Connect\Usage\RejectResponse', $responseReject);

        $this->assertEquals("Test", $responseReject->rejectionnote);
        return $responseReject;
    }
}
