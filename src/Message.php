<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Message
 *      Exceptions used to deliver a message, not for triggering error
 *
 * @package Connect
 */
class Message extends Exception
{
    /**
     * Message constructor
     * @param string $message - Exception message
     * @param string $code - Exception Code - text exception identifier
     * @param object $object - Exception parameter
     */
    public function __construct($message, $code, $object = null)
    {
        parent::__construct($message, $code, $object);
    }
}
