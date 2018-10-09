<?php

namespace Tests\Unit;


use Connect\Fail;

class FailTest extends \Test\TestCase
{
    /**
     * @return Message
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