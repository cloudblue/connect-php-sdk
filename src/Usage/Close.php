<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class Close
 * @package Connect
 */
class Close extends \Connect\Message
{
    /**
     * Skip constructor
     * @param string $message - Usage File close reason, optional
     */
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Usage File Closed', "close");
    }
}