<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\UsageAutomation;

/**
 * Class UsageAutomationBasicsHelper
 * @property \stdClass $std
 * @package Test\Unit
 */
class UsageAutomationBasicsHelper extends UsageAutomation
{
    public function processUsageForListing($listing)
    {
        // TODO: Implement processUsageForListing() method.
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getHttp()
    {
        return $this->http;
    }

    public function getStd()
    {
        return $this->std;
    }
}
