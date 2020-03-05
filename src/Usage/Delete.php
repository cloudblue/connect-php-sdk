<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class Delete
 * @package Connect
 */
class Delete extends \Connect\Message
{
    /**
     * Skip constructor
     * @param string $message - Usage File Delete reason, optional
     */
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Usage File Deleted', "delete");
    }
}
