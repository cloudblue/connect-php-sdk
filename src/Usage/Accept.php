<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class Accept
 * @package Connect
 */
class Accept extends \Connect\Message
{
    public $response;

    /**
     * Accept constructor
     * @param AcceptResponse $response
     */
    public function __construct($response)
    {
        $this->response = $response;
        parent::__construct('Accept Response is required', 'response');
    }
}