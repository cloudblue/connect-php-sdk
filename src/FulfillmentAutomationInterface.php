<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Interface FulfillmentAutomationInterface
 * @package Connect
 */
interface FulfillmentAutomationInterface
{
    /**
     * @param Request $request
     * @return mixed
     * @throws Skip
     * @throws Inquire
     * @throws Fail
     */
    public function processRequest($request);

    /**
     * @param TierConfigRequest $tierConfigRequest
     * @return mixed
     * @throws Skip
     * @throws Inquire
     * @throws Fail
     */
    public function processTierConfigRequest($tierConfigRequest);
}
