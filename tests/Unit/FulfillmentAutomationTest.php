<?php

namespace Test\Unit;

use Connect\Config;

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
        $this->assertInstanceOf('\Connect\Config', $app->config);
        $this->assertNull($app->wrongpropertyorservice);

        $this->assertInstanceOf('\Connect\Logger', $app->getLogger());
        $this->assertInstanceOf('\Connect\Logger', $app->logger);

        $this->assertInstanceOf('\stdClass', $app->getStd());
        $this->assertInstanceOf('\stdClass', $app->std);
    }

    /**
     * @throws \Connect\ConfigException
     */
    public function testLegacyInstantiationConfigFile()
    {
        $app = new AppHelperLegacy(__DIR__ . '/cfg.valid.json');
        $this->assertInstanceOf('\Connect\RequestsProcessor', $app);
    }

    /**
     * @throws \Connect\ConfigException
     */
    public function testLegacyInstantiationObject()
    {
        $app = new AppHelperLegacy(new Config([
            "apiKey" => "ApiKey SU-766-419-989:56fda6081cd5200f089d28f7f9a6d390bf7ffcec",
            "apiEndpoint" => "https://api.connect.cloud.im/public/v1",
            "logLevel" => "info",
            "timeout" => 10,
            "sslVerifyHost" => false
        ]));
        $this->assertInstanceOf('\Connect\RequestsProcessor', $app);
    }
}