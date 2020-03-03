<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Model;
use Connect\Request;

class StructureTest extends \Test\TestCase
{
    private function removeDiscarted($inputArray, $tobediscarted)
    {
        foreach ($tobediscarted as $discart) {
            unset($inputArray[$discart]);
        }
        return $inputArray;
    }

    public function testTierConfigurationRequestModel()
    {
        $tobediscarted = ["params", "tiers/tier2"];
        $apiOutput = json_decode(file_get_contents(__DIR__. '/apiOutput/tier_config_request.json'));
        $tierConfigRequest = Model::modelize('tierConfigRequest', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
            "debug"=>true,
            "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($tierConfigRequest->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed model entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        $difference['removed'] = $this->removeDiscarted($difference['removed'], $tobediscarted);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }

    public function testFulfillmentRequestModel()
    {
        $tobediscarted = ["answered", "asset/params/0/value_choices", "asset/params/1/value_choices", 'asset/tiers/tier2', 'note', 'reason'];
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/fulfillment_request.json'));
        $request = Model::modelize('request', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
                "debug"=>true,
                "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($request->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed model entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        $difference['removed'] = $this->removeDiscarted($difference['removed'], $tobediscarted);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }

    public function testAssetModel()
    {
        $tobediscarted = ['items/2/params', 'items/1/params', 'tiers/tier2'];
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/asset.json'));
        $asset = Model::modelize('asset', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
                "debug"=>true,
                "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($asset->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed model entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        $difference['removed'] = $this->removeDiscarted($difference['removed'], $tobediscarted);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }

    public function testProductModel()
    {
        $tobediscarted = ['customer_ui_settings/languages', 'capabilities/tiers/configs'];
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/product_request.json'));
        $product = Model::modelize('product', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
                "debug"=>true,
                "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($product->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed Product entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        $difference['removed'] = $this->removeDiscarted($difference['removed'], $tobediscarted);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);

        return $this;
    }

    public function testTierConfigurationModel()
    {
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/tierconfiguration.json'));
        $tierConfig = Model::modelize('tierconfig', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
                "debug"=>true,
                "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($tierConfig->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed Tier Config entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }

    public function testUsageFileModel()
    {
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/usagefile_request.json'));
        $usageFile = Model::modelize('file', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
                "debug"=>true,
                "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($usageFile->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed usage file entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }

    public function testListingModel()
    {
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/listing.json'));
        $usageFile = Model::modelize('listing', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
                "debug"=>true,
                "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($usageFile->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed usage file entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }

    public function testGetParamByIdOnConfiguration()
    {
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/fulfillment_request.json'));
        $request = Model::modelize('request', $apiOutput);
        $this->assertEquals('product_value', $request->asset->configuration->getParameterByID('product_configuration')->value);
    }

    public function testGetParamByIdOnItem()
    {
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/fulfillment_request.json'));
        $request = Model::modelize('request', $apiOutput);
        $item = $request->asset->getItemByMPN('MPN-A');
        $this->assertEquals('item', $item->getParameterByID('item_per_marketplace')->value);
    }

    public function testgetRequestParameter()
    {
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/fulfillment_request.json'));
        $request = Model::modelize('request', $apiOutput);
        $this->assertEquals('Fulfillment param', $request->asset->getParameterByID('fulfillment_param_b')->value);
    }

    public function testSubscriptionAssetModel()
    {
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/SubscriptionAsset.json'));
        $subscriptionAsset = Model::modelize('SubscriptionAsset', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
                "debug"=>true,
                "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($subscriptionAsset->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed SubscriptionAsset entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }

    public function testSubscriptionRequestModel()
    {
        $apiOutput = json_decode(file_get_contents(__DIR__.'/apiOutput/SubscriptionRequest.json'));
        $subscriptioRequest = Model::modelize('SubscriptionRequest', $apiOutput);
        $treeWalker = new \TreeWalker(
            array(
                "debug"=>true,
                "returntype"=>"array")
        );
        $difference = $treeWalker->getdiff($subscriptioRequest->toJSON(), $apiOutput);
        if (count($difference['new']) > 0) {
            fwrite(STDOUT, "Removed Subscription Request entries\n");
            fwrite(STDOUT, var_dump($difference['new']));
        }
        $this->assertCount(0, $difference['new']);
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }
}
