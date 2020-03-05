<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Config;

/**
 * Class TierRequestTest
 * @package Test\Unit
 */
class TierRequestTest extends \Test\TestCase
{
    protected function setUp()
    {
        /**
         * change the work dir, by default the default config file
         * must be in the same directory of the entry point.
         */
        chdir(__DIR__);
    }

    public function testCommonUseCasesWithTierRequests()
    {
        $app = new FulfillmentAutomationHelper(new Config('./config.mocked4tierconfig.json'));
        $this->assertInstanceOf('\Connect\FulfillmentAutomation', $app);

        $list = $app->listTierConfigs();
        $this->assertCount(1, $list);
        $this->assertInstanceOf('\Connect\TierConfigRequest', $list[0]);
        $request = $list[0];
        $paramInRequest = $request->getParameterByID('tier2fulfillment');
        $this->assertInstanceOf('\Connect\Param', $paramInRequest);
        $this->assertEquals('Reseller 123', $paramInRequest->value);
        $this->assertEquals('Reseller OLD', $request->configuration->getParameterByID('tier2fulfillment')->value);
        $parameter = $app->getTierParameterByProductAndTierId('tier2fulfillment', 'TA-0-2281-3745-7900', 'PRD-165-377-339');
        $this->assertEquals('Tier 2 Fulfillment', $parameter->title);
        $this->assertNull($request->configuration->getParameterByID('NotFound'));
        $this->assertNull($app->getTierParameterByProductAndTierId('tier2fulfillment', 'TA-0-2281-3745-7900', 'PRD-165-377-340'));
    }
}
