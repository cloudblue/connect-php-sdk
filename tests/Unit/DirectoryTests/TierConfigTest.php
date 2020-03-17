<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\DirectoryTests;

use Connect\Config;
use Connect\ConnectClient;

class TierConfigTest extends \Test\TestCase
{
    public function testGetTierConfigs()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.TierConfigList.json'));
        $tierConfigs = $connectClient->directory->listTierConfigs();
        foreach ($tierConfigs as $tierConfig) {
            $this->assertInstanceOf("\Connect\TierConfig", $tierConfig);
        }
        $this->assertEquals(1, count($tierConfigs));
    }

    public function testGetTierConfigsRql()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.TierConfigList.json'));
        $tierConfigs = $connectClient->directory->listTierConfigs(new \Connect\RQL\Query(['product.id' => 'PRD-866-415-789']));
        foreach ($tierConfigs as $tierConfig) {
            $this->assertInstanceOf("\Connect\TierConfig", $tierConfig);
            $this->assertEquals('PRD-866-415-789', $tierConfig->product->id);
        }
        $this->assertEquals(1, count($tierConfigs));
    }

    public function testGetTierConfigsRqlOverFilterArray()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.TierConfigList.json'));
        $tierConfigs = $connectClient->directory->listTierConfigs(['product.id' => 'PRD-866-415-789']);
        foreach ($tierConfigs as $tierConfig) {
            $this->assertInstanceOf("\Connect\TierConfig", $tierConfig);
            $this->assertEquals('PRD-866-415-789', $tierConfig->product->id);
        }
        $this->assertEquals(1, count($tierConfigs));
    }

    public function testGetTierConfig()
    {
        $connectClient = new ConnectClient(new Config(__DIR__ . '/config.mocked.getTierConfig.json'));
        $product = $connectClient->directory->getTierConfigById('TC-279-636-201');
        $this->assertInstanceOf("\Connect\TierConfig", $product);
    }
}
