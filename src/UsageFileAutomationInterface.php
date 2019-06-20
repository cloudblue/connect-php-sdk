<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Connect\Usage\File;

/**
 * Interface UsageFileAutomationInterface
 * @package Connect
 */
interface UsageFileAutomationInterface
{
    /**
     * @param File $usageFile
     * @return mixed
     */
    public function processUsageFiles($usageFile);
}
