<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\Config;

class AppTest extends \Test\TestCase
{
    /**
     * @throws \Connect\ConfigException
     * @throws \Connect\ConfigPropertyInvalid
     * @throws \Connect\ConfigPropertyMissed
     * @throws \ReflectionException
     */
    public function testLegacyInstantiationConfigFile()
    {
        $app = new AppHelperLegacy(__DIR__ . '/cfg.valid.json');
        $this->assertInstanceOf('\Connect\RequestsProcessor', $app);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \Connect\ConfigPropertyInvalid
     * @throws \Connect\ConfigPropertyMissed
     * @throws \ReflectionException
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