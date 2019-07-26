<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\DirectoryTests;

use Connect\Config;
use Connect\ConnectClient;
use Connect\Item;

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

    public function testCreateRequest()
    {
        $connectClient = new ConnectClient(new Config(__DIR__.'/config.mocked.createRequest.json'));
        $items = array(
            new Item([
                'quantity' => 900,
                'global_id' => 'PRD-863-384-534-0001'
            ]),
            new Item([
                'quantity' => 200,
                'global_id' => 'PRD-863-384-534-0002'
            ])
        );
        $request = new \Connect\Request([
            "type" => "purchase",
            "asset" => [
                "connection" => [
                    "id" => "CT-0000-0000-0000"
                ],
                "external_uid" => "3a477438-e037-4e5a-afaf-b4a56b079d0b",
                "external_id" => "1",
                "items" => $items,
                "params" => array(),
                "tiers" => [
                    "customer" => new \Connect\Tier([
                        "id"=> "TA-0-2431-4295-9364"
                    ]),
                    "tier1" => new \Connect\Tier([
                        "id" => "TA-0-5813-2409-3030"
                    ])
                ]
            ]
        ]);
        $postedRequest = $connectClient->fulfillment->createRequest($request);
        $this->assertInstanceOf('\Connect\Request', $postedRequest);
        return $this;
    }
}
