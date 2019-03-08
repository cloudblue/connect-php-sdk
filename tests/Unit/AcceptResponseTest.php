<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\Usage\AcceptResponse;

/**
 * Class AcceptResponseTest
 * @package Tests\Unit
 */
class AcceptResponseTest extends \Test\TestCase
{

    /**
     * @return AcceptResponse
     */
    public function testInstantiation()
    {
        $responseAccept = new AcceptResponse('Test');

        $this->assertInstanceOf('\Connect\Usage\AcceptResponse', $responseAccept);

        $this->assertEquals("Test", $responseAccept->acceptancenote);
        return $responseAccept;
    }

}