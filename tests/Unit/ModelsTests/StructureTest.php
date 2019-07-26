<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Model;

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
        if (count($difference['removed']) > 0) {
            fwrite(STDOUT, "New model entries\n");
            fwrite(STDOUT, var_dump($difference['removed']));
        }
        $this->assertCount(0, $difference['removed']);
        return $this;
    }
}
