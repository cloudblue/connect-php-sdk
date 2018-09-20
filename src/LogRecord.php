<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * LogRecord class, instance created for every logging action
 *
 * @package Connect
 */
class LogRecord
{
    public $level;
    public $message;
    public $time;

    public function __construct($level, $message, $time = 0)
    {
        $this->level = $level;
        $this->message = $message;
        $this->time = ($time === 0) ? time() : $time;
    }
}