<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Interface FulfillmentAutomationInterface
 * @package Connect
 */
interface FulfillmentAutomationInterface
{
    /**
     * @param $request
     * @return mixed
     * @throws Skip
     * @throws Inquire
     * @throws Fail
     */
    public function processRequest($request);

    /**
     * @param $tierConfigRequest
     * @return mixed
     * @throws Skip
     * @throws Inquire
     * @throws Fail
     */
    public function processTierConfigRequest($tierConfigRequest);
}
