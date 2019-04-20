<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Config;

class ConfigTest extends \Test\TestCase
{
    /**
     * @throws \Connect\ConfigException
     */
    public function testInstantiationConfigFile()
    {
        $cfg = new Config(__DIR__ . '/cfg.valid.json');
        $this->assertInstanceOf('\Connect\Config', $cfg);
    }

    /**
     * @throws \Connect\ConfigException
     */
    public function testInstantiationArray()
    {
        $cfg = new Config([
            "apiKey" => "ApiKey SU-766-419-989:56fda6081cd5200f089d28f7f9a6d390bf7ffcec",
            "apiEndpoint" => "https://api.connect.cloud.im/public/v1",
            "logLevel" => "info",
            "timeout" => 10,
            "sslVerifyHost" => true
        ]);
        $this->assertInstanceOf('\Connect\Config', $cfg);
    }

    /**
     * @throws \Connect\ConfigException
     *
     * @expectedException \Connect\ConfigException
     */
    public function testInvalidArguments()
    {
        new Config(1);
    }

    /**
     * @throws \Connect\ConfigException
     *
     * @expectedException \Connect\ConfigPropertyMissed
     */
    public function testValidateApiKey()
    {
        new Config(__DIR__ . '/cfg.invalid.noapikey.json');
    }

    /**
     * @throws \Connect\ConfigException
     */
    public function testSetRuntimeServices()
    {
        $cfg = new Config(__DIR__ . '/cfg.valid.runtimeservices.json');
        $this->assertInstanceOf('\Connect\Config', $cfg);
        $this->assertInternalType('array', $cfg->runtimeServices);
        $this->assertCount(6, $cfg->runtimeServices);
    }

    /**
     * @throws \Connect\ConfigException
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidRuntimeServices()
    {
        new Config(__DIR__ . '/cfg.invalid.runtimeservices.json');
    }

    /**
     * @throws \Connect\ConfigException
     *
     * @expectedException \Connect\ConfigPropertyMissed
     */
    public function testValidateApiEndpoint()
    {
        new Config(__DIR__ . '/cfg.invalid.noapiendpoint.json');
    }

    /**
     * @throws \Connect\ConfigException
     *
     * @expectedException \Connect\ConfigException
     */
    public function testConfigFileNotFound()
    {
        new Config(__DIR__ . '/cfg.notfound.json');
    }

    /**
     * @throws \Connect\ConfigException
     *
     * @expectedException \Connect\ConfigException
     */
    public function testConfigFileInvalidFormat()
    {
        new Config(__DIR__ . '/cfg.invalid.format.json');
    }

    /**
     * @throws \Connect\ConfigException
     *
     * @expectedException \InvalidArgumentException
     */

    public function testInvalidProductType()
    {
        $cfg = new Config(__DIR__ . '/cfg.valid.runtimeservices.json');
        $cfg->setProducts((int) 10);
    }

    /**
     * @throws \Connect\ConfigException
     */

    public function testSetProductAsString()
    {
        $cfg = new Config(__DIR__ . '/cfg.valid.runtimeservices.json');
        $cfg->setProducts((string)"CN-123-123");
        $this->assertEquals("CN-123-123", $cfg->products[0]);
    }
}
