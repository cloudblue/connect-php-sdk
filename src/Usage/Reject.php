<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class Reject
 * @package Connect
 */
class Reject extends \Connect\Message
{
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Usage data is not correct', "reject");
    }
}
