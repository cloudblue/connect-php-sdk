<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\Param;

class ParamTest extends \Test\TestCase
{
    /**
     * @return Param
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

        $this->assertInstanceOf('\Connect\Param', $param);

        $this->assertEquals(1, $param->id);
        $this->assertEquals('param1', $param->name);
        $this->assertEquals('param number 1', $param->description);
        $this->assertEquals(1, $param->value);
        $this->assertEquals('', $param->value_error);

        return $param;
    }

    /**
     * @param Param $param
     *
     * @depends testInstantiation
     */
    public function testSetErrorMessage(Param $param)
    {
        $this->assertInstanceOf('\Connect\Param', $param->error('Some error'));
        $this->assertEquals('Some error', $param->value_error);
    }

    /**
     * @param Param $param
     *
     * @depends testInstantiation
     */
    public function testSetValueSet(Param $param)
    {
        $this->assertInstanceOf('\Connect\Param', $param->value('Some new value'));
        $this->assertEquals('Some new value', $param->value);
    }

}