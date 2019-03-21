<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Usage\FileRetrieveException;

/**
 * Class FileRetrieveExceptionTest
 * @package Tests\Unit
 */
class FileRetrieveExceptionTest extends \Test\TestCase
{
    public function testInstantiation()
    {
        $error = new FileRetrieveException("error");

        $this->assertInstanceOf('\Connect\Usage\FileRetrieveException', $error);

        $this->assertEquals("error", $error->getMessage());
        $this->assertEquals('fileretrieve', $error->getCode());
        $this->assertEquals(null, $error->getObject());
        return $error;
    }
}
