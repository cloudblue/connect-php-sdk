<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\FulfillmentAutomation;

/**
 * Class FulfillmentAutomationHelper
 * @property \stdClass $std
 * @package Test\Unit
 */
class FulfillmentAutomationHelper extends FulfillmentAutomation
{
    public function processRequest($request)
    {
        //do magic
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getCurl()
    {
        return $this->curl;
    }

    public function getStd()
    {
        return $this->std;
    }
}