<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use \Connect\Usage\FileCreationException;

/**
 * Interface UsageAutomationInterface
 * @package Connect
 */
interface UsageAutomationInterface
{

    /**
     * @param Listing $listing
     * @return boolean
     * @return string
     * @throws FileCreationException
     */
    public function processUsageForListing($listing);
}
