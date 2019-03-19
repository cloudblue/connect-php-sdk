<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class RejectResponse
 * @package Connect
 */
class RejectResponse
{

    /**
     * @var null|string
     */
    public $rejectionnote = "Usage data is not correct";

    /**
     * ActivationTileResponse constructor.
     * @param null $msg
     */
    public function __construct($msg = null)
    {
        if ($msg) {
            $this->rejectionnote = $msg;
        }
    }
}
