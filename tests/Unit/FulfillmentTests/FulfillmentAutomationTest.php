<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Config;

require_once __DIR__."/FulfillmentAutomationHelper.php";
require_once __DIR__."/RequestProcessorHelper.php";

/**
 * Class FulfillmentAutomationTest
 * @package Test\Unit
 */
class FulfillmentAutomationTest extends \Test\TestCase
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
        $app = new FulfillmentAutomationHelper();
        $this->assertInstanceOf('\Connect\FulfillmentAutomation', $app);

        $this->assertInstanceOf('\Connect\Config', $app->getConfig());
        $this->assertInstanceOf('\Connect\Config', $app->fulfillment->getConfig());
        $this->assertInstanceOf('\Connect\Config', $app->config);
        $this->assertNull($app->wrongpropertyorservice);

        $this->assertInstanceOf('\Connect\Logger', $app->getLogger());
        $this->assertInstanceOf('\Connect\Logger', $app->fulfillment->getLogger());
        $this->assertInstanceOf('\Connect\Logger', $app->logger);

        $this->assertInstanceOf('\GuzzleHttp\Client', $app->getHttp());
        $this->assertInstanceOf('\GuzzleHttp\Client', $app->fulfillment->getHttp());
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
        $app = new FulfillmentAutomationHelper(new Config('./config.mocked.json'));
        $this->assertInstanceOf('\Connect\FulfillmentAutomation', $app);

        $app->process();
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRenderTemplate()
    {
        $app = new FulfillmentAutomationHelper(new Config('./config.mocked.json'));
        $this->assertInstanceOf('\Connect\FulfillmentAutomation', $app);

        $app->process();
    }

    /**
     * @throws \Connect\ConfigException
     */
    public function testLegacyInstantiationConfigFile()
    {
        $app = new RequestProcessorHelper(__DIR__ . '/cfg.valid.json');
        $this->assertInstanceOf('\Connect\RequestsProcessor', $app);
    }

    /**
     * @throws \Connect\ConfigException
     */
    public function testLegacyInstantiationObject()
    {
        $app = new RequestProcessorHelper(new Config([
            "apiKey" => "ApiKey SU-XXX-1234567890",
            "apiEndpoint" => "http://api.branding.cloud/public/v42",
            "logLevel" => "info",
            "timeout" => 10,
            "sslVerifyHost" => false
        ]));
        $this->assertInstanceOf('\Connect\RequestsProcessor', $app);
    }

    /**
     * @throws \Connect\ConfigException
     */
    public function testLegacyInstantiationCfgFile()
    {
        $app = new RequestProcessorHelper();
        $this->assertInstanceOf('\Connect\RequestsProcessor', $app);
    }

    public function testFakeShurtcut()
    {
        //to be deprecated, please always use right services and not the shortcuts
        $app = new RequestProcessorHelper();
        $app->fulfillment->__call("wrong", array());
        $app->usage->__call("wrong", array());
        $this->assertInstanceOf('Test\Unit\RequestProcessorHelper', $app);
    }

    public function testTemplateRetrive()
    {
        $app = new RequestProcessorHelper(__DIR__ . '/config.mocked4tmpl.json');
        $template = $app->fulfillment->renderTemplate('1', 'PR-123-123-123');
        $this->assertStringStartsWith("# tmpl", $template);
    }
}
