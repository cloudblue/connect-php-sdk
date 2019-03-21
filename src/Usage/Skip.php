<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class Skip
 * @package Connect
 */
class Skip extends \Connect\Message
{
    /**
     * Skip constructor
     * @param string $message - Usage File skipping reason, optional
     */
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Usage File skipped', "skip");
    }
}
