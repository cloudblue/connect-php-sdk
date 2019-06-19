<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Usage\Accept;

/**
 * Class AcceptTest
 * @package Tests\Unit
 */
class AcceptTest extends \Test\TestCase
{

    /**
     * @return Accept
     */
    public function testUsageInstantiation()
    {
        $responseAccept = new Accept('TD-123');

        $this->assertInstanceOf('\Connect\Usage\Accept', $responseAccept);

        $this->assertEquals("TD-123", $responseAccept->getMessage());
        return $responseAccept;
    }
}
