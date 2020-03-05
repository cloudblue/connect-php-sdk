<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\SubscriptionTests;

use Connect\Config;
use Connect\ConnectClient;
use Connect\Model;
use Connect\RQL\Query;

class SubscriptionRequestTest extends \Test\TestCase
{
    public function testSetAttributes()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.testSetAttributes.json'));
        $request = $connectClient->subscriptions->getSubscriptionRequestById('BRV-0000-0000-0002');
        $this->assertInstanceOf('Connect\Subscription\SubscriptionRequest', $request);
        $model = new Model([
            "something" => "value"
        ]);
        $request->setVendorAttributes($model);
        $this->assertEquals($request->attributes->vendor->something, "value");
        $request->setProviderAttributes($model);
        $this->assertEquals($request->attributes->provider->something, "value");
    }

    public function testOperationsSubscriptions()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.testOperations.json'));
        $requests = $connectClient->subscriptions->listSubscriptionRequests();
        foreach ($requests as $request) {
            $this->assertInstanceOf('Connect\Subscription\SubscriptionRequest', $request);
        }
        $requests = $connectClient->subscriptions->listSubscriptionRequests(new Query(['id' => 'BRV-0000-0000-0002']));
        foreach ($requests as $request) {
            $this->assertInstanceOf('Connect\Subscription\SubscriptionRequest', $request);
            $this->assertEquals('BRV-0000-0000-0002', $request->id);
        }
        $requests = $connectClient->subscriptions->listSubscriptionRequests(['id' => 'BRV-0000-0000-0002']);
        $this->assertInstanceOf('Connect\Subscription\SubscriptionRequest', $requests[0]);
        $assetList = $connectClient->subscriptions->listSubscriptionAssets();
        foreach ($assetList as $asset) {
            $this->assertInstanceOf('Connect\Subscription\SubscriptionAsset', $asset);
        }
        $assetList = $connectClient->subscriptions->listSubscriptionAssets(new Query(["id" => "AS-1611-4204-8054"]));
        foreach ($assetList as $asset) {
            $this->assertInstanceOf('Connect\Subscription\SubscriptionAsset', $asset);
        }
        $assetList = $connectClient->subscriptions->listSubscriptionAssets(["id" => "AS-1611-4204-8054"]);
        $this->assertInstanceOf('Connect\Subscription\SubscriptionAsset', $assetList[0]);
        $asset = $connectClient->subscriptions->getSubscriptionAssetById("AS-1611-4204-8054");
        $this->assertInstanceOf('Connect\Subscription\SubscriptionAsset', $asset);
        $this->assertEquals("AS-1611-4204-8054", $asset->id);
    }
}
