<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Param
 * @package Connect
 */
class Param
{
    public $id;
    public $name;
    public $description;
    public $value;
    public $value_error;

    /**
     * @var ValueOption{value}
     */
    public $value_choices;

    /**
     * Param constructor
     * @param string $id - parameter ID (optional)
     * @param string $value - parameter Value (optional)
     */
    function __construct($id = null, $value = null)
    {
        $this->id = $id;
        $this->value = $value;
    }

    /**
     * Assign error on parameter
     * @param string $msg - Error message to assign
     * @return $this - Same Param object, for chain assignments like $param->error('err')->value('xxx')
     */
    function error($msg)
    {
        $this->value_error = $msg;
        return $this;
    }

    /**
     * Assign value on parameter
     * @param string $newValue - Value for parameter to assign
     * @return $this - Same Param object, for chain assignments like $param->error('err')->value('xxx')
     */
    function value($newValue)
    {
        $this->value = $newValue;
        return $this;
    }
}