<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
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
    public
    function __construct($message, $code = 'unknown', $object = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->object = $object;
    }

    /**
     * Get Exception parameter object
     * @return null|object
     */
    function getObject()
    {
        return $this->object;
    }
}

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

/**
 * Class Inquire
 *      Inquire for some more data from customer
 *      request to be moved into inquire status
 * @package Connect
 */
class Inquire extends Message
{
    var $params;

    /**
     * Inquire constructor
     * @param Param[] $params - Parameters to inquiry, updated in request
     */
    public function __construct($params)
    {
        $this->params = $params;
        parent::__construct('Activation parameters are required', 'inqury');
    }
}

/**
 * Class Skip
 *      Skip request processing this time,
 *      request remains in pending status
 * @package Connect
 */
class Skip extends Message
{
    /**
     * Skip constructor
     * @param string $message - Request skipping reason, optional
     */
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Request skipped', "skip");
    }
}

/**
 * Class Fail
 *      Indicate request processing failure
 *      request to be moved into failed status, no further request processing
 * @package Connect
 */
class Fail extends Message
{
    /**
     * Fail constructor
     * @param string $message - Request failure explanation, optional
     */
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Request processing failed', 'fail');
    }
}
