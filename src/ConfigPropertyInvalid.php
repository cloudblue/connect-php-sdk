<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ConfigPropertyInvalid
 *      Configuration property invalid exception
 * @package Connect
 */
class ConfigPropertyInvalid extends ConfigException
{
    public function __construct($message, $prop, $value)
    {
        parent::__construct("Invalid property value '$value' " . $message, $prop);
    }
}