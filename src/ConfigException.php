<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ConfigException
 *      Generic configuration exception
 *
 * @package Connect
 */
class ConfigException extends Exception
{
    private $property;

    public function __construct($message, $prop = null)
    {
        parent::__construct($message, 'config');
        $this->property = $prop;

        if ($prop) {
            $this->message = $message . " for property " . $prop;
        }
    }
}