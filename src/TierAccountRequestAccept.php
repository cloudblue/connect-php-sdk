<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

class TierAccountRequestAccept extends Message
{
    public function __construct($message = null)
    {
        parent::__construct($message ? $message : 'TAR Processed', "accepted");
    }
}
