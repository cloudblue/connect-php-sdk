<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class Accept
 * @package Connect
 */
class Accept extends \Connect\Message
{
    /**
     * Accept constructor.
     * @param string $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Usage data is correct', "accept");
    }
}
