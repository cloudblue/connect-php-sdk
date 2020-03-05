<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ConfigPropertyMissed
 *      Configuration property missed exception
 *
 * @package Connect
 */
class ConfigPropertyMissed extends ConfigException
{
    public function __construct($prop)
    {
        parent::__construct('Value is not set ', $prop);
    }
}
