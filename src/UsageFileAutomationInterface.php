<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Interface UsageFileAutomationInterface
 * @package Connect
 */
interface UsageFileAutomationInterface
{
    /**
     * @param $usageFile
     * @return mixed
     */
    public function processUsageFiles($usageFile);
}
