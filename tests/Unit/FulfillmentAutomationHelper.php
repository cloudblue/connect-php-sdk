<?php

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