<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Usage\FileCreationException;

/**
 * Class FileCreationExceptionTest
 * @package Tests\Unit
 */
class FileCreationExceptionTest extends \Test\TestCase
{
    public function testInstantiation()
    {
        $error = new FileCreationException("error");

        $this->assertInstanceOf('\Connect\Usage\FileCreationException', $error);

        $this->assertEquals("error", $error->getMessage());
        $this->assertEquals('filecreation', $error->getCode());
        $this->assertEquals(null, $error->getObject());
        return $error;
    }
}
