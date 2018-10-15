<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

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