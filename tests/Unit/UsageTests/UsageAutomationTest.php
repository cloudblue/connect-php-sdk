<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

require_once __DIR__."/UsageAutomationHelper.php";
use Connect\Config;

class UsageAutomationTest extends \Test\TestCase
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
     * @return UsageAutomationHelper
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUsageAutomation()
    {
        $app = new UsageAutomationHelper(new Config('./config.mocked4usageautomation.json'));
        $this->assertInstanceOf('Test\Unit\UsageAutomationHelper', $app);
        $app->process();
        return $app;
    }

    public function testUsageConfig()
    {
        $app = new UsageAutomationHelper();
        $this->assertInstanceOf('Test\Unit\UsageAutomationHelper', $app);
        return $app;
    }
}
