<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\FulfillmentAutomation;

/**
 * Class TierRequestHelper
 * @property \stdClass $std
 * @package Test\Unit
 */
class TierRequestHelper extends FulfillmentAutomation
{
    /**
     * @param $request
     * @return \Connect\ActivationTemplateResponse|string
     * @throws \Connect\Fail
     * @throws \Connect\Inquire
     * @throws \Connect\Skip
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processRequest($request)
    {
    }

    /**
     * @param $tierConfigRequest
     * @return \Connect\ActivationTileResponse|string
     */
    public function processTierConfigRequest($tierConfigRequest)
    {
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
