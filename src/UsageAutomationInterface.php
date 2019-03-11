<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Interface UsageAutomationInterface
 * @package Connect
 */
interface UsageAutomationInterface
{

    /**
     * @param $listing
     * @return boolean
     * @return string
     * @throws  \Connect\Usage\FileCreationException
     */
    public function processUsageForListing($listing);

}