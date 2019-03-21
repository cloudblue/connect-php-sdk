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
    public function processRequest($request);

    public function processTierConfigRequest($tierConfigRequest);
}
