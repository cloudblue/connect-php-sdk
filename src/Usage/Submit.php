<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class Submit
 * @package Connect
 */
class Submit extends \Connect\Message
{
    /**
     * Skip constructor
     * @param string $message - Usage File submit reason, optional
     */
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Usage File Submited', "submit");
    }
}
