<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Param
 * @package Connect
 */
class Param extends Model
{
    public $id;
    public $name;
    public $description;
    public $value;
    public $value_error;

    /**
     * @var ValueChoice{value}
     */
    public $value_choices;

    /**
     * Assign error on parameter
     * @param string $msg - Error message to assign
     * @return $this - Same Param object, for chain assignments like $param->error('err')->value('xxx')
     */
    public function error($msg)
    {
        $this->value_error = $msg;
        return $this;
    }

    /**
     * Assign value on parameter
     * @param string $newValue - Value for parameter to assign
     * @return $this - Same Param object, for chain assignments like $param->error('err')->value('xxx')
     */
    public function value($newValue)
    {
        $this->value = $newValue;
        return $this;
    }
}