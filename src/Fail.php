<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

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