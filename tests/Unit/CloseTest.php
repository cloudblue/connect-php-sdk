<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Usage\Close;

/**
 * Class CloseTest
 * @package Tests\Unit
 */
class CloseTest extends \Test\TestCase
{
    /**
     * @return mixed
     */
    public function testInstantiation()
    {
        $close = new Close();

        $this->assertInstanceOf('\Connect\Usage\Close', $close);

        $this->assertEquals("Usage File Closed", $close->getMessage());
        $this->assertEquals('close', $close->getCode());
        $this->assertEquals(null, $close->getObject());
        return $close;
    }
}
