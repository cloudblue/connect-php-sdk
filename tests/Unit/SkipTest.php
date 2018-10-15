<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\Skip;

/**
 * Class SkipTest
 * @package Tests\Unit
 */
class SkipTest extends \Test\TestCase
{
    /**
     * @return mixed
     */
    public function testInstantiation()
    {
        $skip = new Skip();

        $this->assertInstanceOf('\Connect\Skip', $skip);

        $this->assertEquals("Request skipped", $skip->getMessage());
        $this->assertEquals('skip', $skip->getCode());
        $this->assertEquals(null, $skip->getObject());
        return $skip;
    }

}