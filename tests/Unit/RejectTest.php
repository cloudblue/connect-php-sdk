<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\Usage\Reject;

/**
 * Class RejectTest
 * @package Tests\Unit
 */
class RejectTest extends \Test\TestCase
{

    /**
     * @return Reject
     */
    public function testInstantiation()
    {
        $responseReject = new Reject('TD-123');

        $this->assertInstanceOf('\Connect\Usage\Reject', $responseReject);

        $this->assertEquals("TD-123", $responseReject->response);
        return $responseReject;
    }

}