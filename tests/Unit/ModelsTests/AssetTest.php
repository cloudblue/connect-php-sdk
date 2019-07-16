<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Asset;
use Connect\Item;
use Connect\Param;

class AssetTest extends \Test\TestCase
{
    /**
     * @return Asset
     */
    public function testInstantiation()
    {
        $asset = new Asset([
            'id' => 111,
            'params' => [
                new Param([
                    'id' => 111,
                    'value' => 'one'
                ]),
                new Param([
                    'id' => 222,
                    'value' => 'one'
                ]),
                new Param([
                    'id' => 333,
                    'value' => 'one'
                ])
            ],
            'items' => [
                new Item([
                    'id' => 111,
                    'quantity' => 1,
                    'mpn' => '111'
                ]),
                new Item([
                    'id' => 222,
                    'quantity' => 2,
                    'mpn' => '222'
                ]),
                new Item([
                    'id' => 333,
                    'quantity' => 3,
                    'mpn' => '333',
                    'global_id' => 'PRD-123-123-123-1'
                ])
            ],
        ]);

        $this->assertInstanceOf('\Connect\Asset', $asset);

        $this->assertEquals(111, $asset->id);
        $this->assertCount(3, $asset->params);
        $this->assertCount(3, $asset->items);

        return $asset;
    }

    /**
     * @param Asset $asset
     *
     * @depends testInstantiation
     */
    public function testGetParameterById(Asset $asset)
    {
        $this->assertInstanceOf('\Connect\Param', $asset->getParameterByID(222));
        $this->assertEquals(222, $asset->getParameterByID(222)->id);
        $this->assertNull($asset->getParameterByID(999));
    }

    /**
     * @param Asset $asset
     *
     * @depends testInstantiation
     */
    public function testGetItemById(Asset $asset)
    {
        $this->assertInstanceOf('\Connect\Item', $asset->getItemByID(333));
        $this->assertEquals(333, $asset->getItemByID(333)->id);
        $this->assertNull($asset->getItemByID(999));
    }

    /**
     * @param Asset $asset
     *
     * @depends testInstantiation
     */
    public function testGetItemByMpn(Asset $asset)
    {
        $this->assertInstanceOf('\Connect\Item', $asset->getItemByMPN('333'));
        $this->assertEquals(333, $asset->getItemByMPN('333')->id);
        $this->assertNull($asset->getItemByMPN('999'));
    }

    /**
     * @param Asset $asset
     *
     * @depends testInstantiation
     */
    public function testGetItemByGlobalID(Asset $asset)
    {
        $this->assertInstanceOf('\Connect\Item', $asset->getItemByGlobalID('PRD-123-123-123-1'));
        $this->assertEquals(333, $asset->getItemByGlobalID('PRD-123-123-123-1')->id);
        $this->assertNull($asset->getItemByGlobalID('999'));
    }
}

