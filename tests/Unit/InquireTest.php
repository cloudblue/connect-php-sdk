<?php

namespace Tests\Unit;


use Connect\Inquire;
use Connect\Param;

class InquireTest extends \Test\TestCase
{
    /**
     * @return Message
     */
    public function testInstantiation()
    {
        $param = new Param([
            'id' => 1,
            'name' => 'param1',
            'description' => 'param number 1',
            'value' => 1,
            'value_error' => '',
        ]);
        $inquire = new Inquire([$param]);

        $this->assertInstanceOf('\Connect\Inquire', $inquire);
        $this->assertInstanceOf('\Connect\Param', $inquire->params[0]);
        $this->assertEquals("Activation parameters are required", $inquire->getMessage());
        $this->assertEquals('inquiry', $inquire->getCode());
        $this->assertEquals(null, $inquire->getObject());
        return $inquire;
    }

}
