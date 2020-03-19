<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

class TierAccountRequestIgnore extends Message
{
    /**
     * TierAccountRequestIgnore constructor
     * @param string $message - Request failure explanation, optional
     */
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'This product can not process account change requests', 'ignore');
    }
}
