<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
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
        switch ($request->id) {
            case 'PR-5620-6510-1234':
                return "Done";
            case 'PR-5620-6510-TMPL':
                throw new \Connect\Skip("Skipping rendertemplate to be tested aside");
            case 'PR-5620-6510-INQUIRE':
                throw new \Connect\Inquire([
                    new \Connect\Param([
                        'id' => 'howyoufeel',
                        'value_error' => 'I dont like how you feel today.'
                    ])
                ]);
            case 'PR-5620-6510-FAIL':
                throw new \Connect\Fail("Testing failures");
            case 'PR-5620-6510-SKIP':
                throw new \Connect\Skip("Testing skipping");
            default:
                return new \Connect\ActivationTemplateResponse("TL-1234-4321");
        }
    }

    /**
     * @param $tierConfigRequest
     * @return \Connect\ActivationTileResponse|string
     */
    public function processTierConfigRequest($tierConfigRequest)
    {
        switch ($tierConfigRequest->id) {
            case 'TC-105-196-018':
                return "Done";
            case 'TCR-381-349-991-inquire':
                throw new \Connect\Inquire(([
                    new \Connect\Param([
                        'id' => 'param_a',
                        'value_error' => 'Not valid.'
                    ])
                ]));
            case 'TCR-381-349-991-skip':
                throw new \Connect\Skip("Testing skipping");
            case 'TCR-381-349-991-fail':
                throw new \Connect\Fail("Testing failures");
            case 'TCR-381-349-991-nomessage':
                return;
            case 'TCR-381-349-991-templateresponse':
                return new \Connect\ActivationTemplateResponse('TL-1234-1234');
            default:
                return new \Connect\ActivationTileResponse("TL-1234-1234");
        }
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
