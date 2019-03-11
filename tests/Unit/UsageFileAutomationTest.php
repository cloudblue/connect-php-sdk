<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Config;

/**
 * Class UsageFileAutomationTest
 * @package Test\Unit
 */
class UsageFileAutomationTest extends \Test\TestCase
{
    protected function setUp()
    {
        /**
         * change the work dir, by default the default config file
         * must be in the same directory of the entry point.
         */
        chdir(__DIR__);
    }

    /**
     * @throws \Connect\ConfigException
     */
    public function testInstantiationDefault()
    {
        $app = new UsageFileAutomationHelper();
        $this->assertInstanceOf('\Connect\UsageFileAutomation', $app);

        $this->assertInstanceOf('\Connect\Config', $app->getConfig());
        $this->assertInstanceOf('\Connect\Config', $app->config);
        $this->assertNull($app->wrongpropertyorservice);

        $this->assertInstanceOf('\Connect\Logger', $app->getLogger());
        $this->assertInstanceOf('\Connect\Logger', $app->logger);

        $this->assertInstanceOf('\GuzzleHttp\Client', $app->getHttp());
        $this->assertInstanceOf('\GuzzleHttp\Client', $app->http);

        $this->assertInstanceOf('\stdClass', $app->getStd());
        $this->assertInstanceOf('\stdClass', $app->std);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCommonUseCases()
    {
        $app = new UsageFileAutomationHelper(new Config('./config.mocked4usagefileautomation.json'));
        $this->assertInstanceOf('\Connect\UsageFileAutomation', $app);

        $app->process();
    }
}