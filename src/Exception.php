<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Exception
 *      Basic Connect SDK Exception class
 * @package Connect
 */
class Exception extends \Exception
{
    protected $object;

    /**
     * Exception constructor
     * @param string $message - Exception message
     * @param string $code - Exception Code - text exception identifier
     * @param object $object - Exception parameter
     */
    public function __construct($message, $code = 'unknown', $object = null)
    {
        parent::__construct();
        $this->code = $code;
        $this->message = $message;
        $this->object = $object;
    }

    /**
     * Get Exception parameter object
     * @return null|object
     */
    public function getObject()
    {
        return $this->object;
    }
}
