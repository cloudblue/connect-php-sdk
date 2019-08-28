<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Usage\Skip;

/**
 * Class SkipTest
 * @package Tests\Unit
 */
class UsageSkipTest extends \Test\TestCase
{
    /**
     * @return mixed
     */
    public function testInstantiation()
    {
        $skip = new Skip();

        $this->assertInstanceOf('\Connect\Usage\Skip', $skip);

        $this->assertEquals("Usage File skipped", $skip->getMessage());
        $this->assertEquals('skip', $skip->getCode());
        $this->assertEquals(null, $skip->getObject());
        return $skip;
    }
}
