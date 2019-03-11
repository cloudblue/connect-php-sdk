<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\Usage\Submit;

/**
 * Class SubmitTest
 * @package Tests\Unit
 */
class SubmitTest extends \Test\TestCase
{

    /**
     * @return Submit
     */
    public function testInstantiation()
    {
        $submit = new Submit();

        $this->assertInstanceOf('\Connect\Usage\Submit', $submit);

        $this->assertEquals("Usage File Submited", $submit->getMessage());
        $this->assertEquals('submit', $submit->getCode());
        $this->assertEquals(null, $submit->getObject());
        return $submit;
    }

}