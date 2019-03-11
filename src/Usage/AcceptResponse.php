<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class AcceptResponse
 * @package Connect
 */
class AcceptResponse
{

    /**
     * @var null|string
     */
    public $acceptancenote = "Usage data is correct";

    /**
     * ActivationTileResponse constructor.
     * @param null $msg
     */
    public function __construct($msg = null)
    {
        if ($msg) {
            $this->acceptancenote = $msg;
        }
    }

}