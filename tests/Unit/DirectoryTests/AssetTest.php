<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\DirectoryTests;

use Connect\Config;
use Connect\ConnectClient;

class AssetTest extends \Test\TestCase
{
    public function testGetInstance()
    {
        $assets = ConnectClient::getInstance(new Config(__DIR__. '/config.mocked.json'))->directory->listAssets();
        $this->assertInternalType("array", $assets);
    }

    public function testGetAssets()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.json'));
        $assets = $connectClient->directory->listAssets();
        foreach ($assets as $asset) {
            $this->assertInstanceOf("\Connect\Asset", $asset);
        }
        $this->assertEquals(10, count($assets));
    }

    public function testGetAssetandRequests()
    {
        $connectClient = new ConnectClient(new Config(__DIR__.'/config.mocked.AssetandRequests.json'));
        $asset = $connectClient->directory->getAssetById('AS-463-716-763-4');
        $this->assertInstanceOf('\Connect\Asset', $asset);
        $this->assertEquals('AS-463-716-763-4', $asset->id);
        $requests = $asset->getRequests();
        foreach ($requests as $request) {
            $this->assertInstanceOf("\Connect\Request", $request);
        }
        $this->assertEquals(1, count($requests));
        $request = $connectClient->fulfillment->getRequest('PR-3505-8841-4044-001');
        $this->assertInstanceOf('\Connect\Request', $request);
    }

    public function testNoRequestsDueNewAssetObject()
    {
        $asset = new \Connect\Asset();
        $requests = $asset->getRequests();
        $this->assertEquals(0, count($requests));
    }
}
