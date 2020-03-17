<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Usage\Delete;

/**
 * Class DeleteTest
 * @package Tests\Unit
 */
class DeleteTest extends \Test\TestCase
{
    /**
     * @return mixed
     */
    public function testInstantiation()
    {
        $delete = new Delete();

        $this->assertInstanceOf('\Connect\Usage\Delete', $delete);

        $this->assertEquals("Usage File Deleted", $delete->getMessage());
        $this->assertEquals('delete', $delete->getCode());
        $this->assertEquals(null, $delete->getObject());
        return $delete;
    }
}
