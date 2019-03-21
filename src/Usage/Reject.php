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
    public $response;

    /**
     * Accept constructor
     * @param RejectResponse $response
     */
    public function __construct($response)
    {
        $this->response = $response;
        parent::__construct('Accept Response is required', 'rejectresponse');
    }
}
