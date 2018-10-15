<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\Fail;

/**
 * Class FailTest
 * @package Tests\Unit
 */
class FailTest extends \Test\TestCase
{

    /**
     * @return Fail
     */
    public function testInstantiation()
    {
        $fail = new Fail();

        $this->assertInstanceOf('\Connect\Fail', $fail);

        $this->assertEquals("Request processing failed", $fail->getMessage());
        $this->assertEquals('fail', $fail->getCode());
        $this->assertEquals(null, $fail->getObject());
        return $fail;
    }

}