<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Interface TierAccountRequestsAutomationInterface
 * @package Connect
 */
interface TierAccountRequestsAutomationInterface
{
    /**
     * @param TierAccountRequest $request
     * @return mixed
     */
    public function processTierAccountRequest(TierAccountRequest $request);
}
