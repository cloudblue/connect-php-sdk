<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Request;

/**
 * Class RequestStructureTest
 * @package Test\Unit
 */
class RequestStructureTest extends \Test\TestCase
{
    public function testRequestStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);

        $this->assertInstanceOf('\Connect\Request', $request);
        $this->assertEquals('2018-12-24T14:30:19.954556+00:00', $request->updated);
        $this->assertEquals('2018-12-24T14:30:19.954503+00:00', $request->created);
        $this->assertEquals('test', $request->activation_key);
        $this->assertEquals('renew', $request->type);
        $this->assertEquals('approved', $request->status);
        $this->assertEquals('PR-2147-8111-7564', $request->id);
        $this->assertEquals('2ec774dd-219c-4f80-8c87-3c3fc1e3edf9', $request->asset->external_uid);


        $this->assertInstanceOf('\Connect\Tiers', $request->asset->tiers);
        $this->assertInstanceOf('\Connect\Tier', $request->asset->tiers->tier1);
    }

    public function testMarketPlaceStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        $this->assertInstanceOf('\Connect\MarketPlace', $request->marketplace);
        $this->assertEquals('MP-00000', $request->marketplace->id);
        $this->assertEquals('ACME Marketplace', $request->marketplace->name);
    }

    public function testContractStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        $this->assertInstanceOf('\Connect\Contract', $request->contract);
        $this->assertEquals('CRD-00000-00000-00000', $request->contract->id);
        $this->assertEquals('ACME Distribution Contract', $request->contract->name);
    }

    public function testAssetStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        $this->assertInstanceOf('\Connect\Asset', $request->asset);
        $this->assertEquals('AS-277-060-219-3', $request->asset->id);
        $this->assertEquals('1000728', $request->asset->external_id);
    }

    public function testConnectionStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        $this->assertInstanceOf('\Connect\Connection', $request->asset->connection);
        $this->assertEquals('CT-9213-9369', $request->asset->connection->id);
        $this->assertEquals('production', $request->asset->connection->type);
        $this->assertInstanceOf('\Connect\Provider', $request->asset->connection->provider);
        $this->assertEquals('PA-425-033', $request->asset->connection->provider->id);
        $this->assertEquals('IMCDemos Testing Account', $request->asset->connection->provider->name);
        $this->assertInstanceOf('\Connect\Vendor', $request->asset->connection->vendor);
        $this->assertEquals('VA-004-290', $request->asset->connection->vendor->id);
        $this->assertEquals('Marc FSG', $request->asset->connection->vendor->name);
    }

    public function testProductStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        $this->assertInstanceOf('\Connect\Product', $request->asset->product);
        $this->assertEquals('CN-543-324-128', $request->asset->product->id);
        $this->assertEquals('fallball QS', $request->asset->product->name);
    }

    public function testItemsStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        foreach ($request->asset->items as $items) {
            $this->assertInstanceOf('\Connect\Item', $items);
        }
        $item = $request->asset->getItemByID('DISKSPACE');
        $this->assertInstanceOf('\Connect\Item', $item);
        $this->assertEquals('DISKSPACE', $item->id);
        $this->assertEquals('Disc Space', $item->mpn);
        $this->assertEquals('10', $item->old_quantity);
        $this->assertEquals('10', $item->quantity);
        $this->assertInstanceOf('\Connect\Renewal', $item->renewal);
        $this->assertEquals('2018-12-24T00:00:00Z', $item->renewal->from);
        $this->assertEquals('2019-01-24T00:00:00Z', $item->renewal->to);
        $this->assertEquals('month', $item->renewal->period_uom);
        $this->assertEquals(1, $item->renewal->period_delta);
        $item = $request->asset->getItemByMPN('Disc Space');
        $this->assertInstanceOf('\Connect\Item', $item);
    }

    public function testParamsStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        foreach ($request->asset->params as $param) {
            $this->assertInstanceOf('\Connect\Param', $param);
        }
        $param = $request->asset->getParameterByID('email');
        $this->assertEquals('email', $param->id);
        $this->assertEquals('Fallball requires an email address for the administrator of the service', $param->description);
        $this->assertEquals('Especify the email of the administrator', $param->name);
        $this->assertEquals('text', $param->type);
        $this->assertEquals('renewalstest123@gmail.com', $param->value);
        $this->assertEquals('', $param->value_error);
    }

    public function testCustomerStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        $this->assertInstanceOf('Connect\Customer', $request->asset->tiers->customer);
        $this->assertEquals('TA-0-4828-4250-6919', $request->asset->tiers->customer->id);
        $this->assertEquals('fallball renewals test', $request->asset->tiers->customer->name);
        $this->assertEquals('1000242', $request->asset->tiers->customer->external_id);
        $this->assertInstanceOf('\Connect\ContactInfo', $request->asset->tiers->customer->contact_info);
        $this->assertEquals('some address', $request->asset->tiers->customer->contact_info->address_line1);
        $this->assertEquals('', $request->asset->tiers->customer->contact_info->address_line2);
        $this->assertEquals('Schenectady', $request->asset->tiers->customer->contact_info->city);
        $this->assertEquals('us', $request->asset->tiers->customer->contact_info->country);
        $this->assertEquals('CA', $request->asset->tiers->customer->contact_info->state);
        $this->assertEquals('12345', $request->asset->tiers->customer->contact_info->postal_code);
        $this->assertInstanceOf('\Connect\Contact', $request->asset->tiers->customer->contact_info->contact);
        $this->assertEquals('renewalstest123@gmail.com', $request->asset->tiers->customer->contact_info->contact->email);
        $this->assertEquals('Renewals', $request->asset->tiers->customer->contact_info->contact->first_name);
        $this->assertEquals('Test', $request->asset->tiers->customer->contact_info->contact->last_name);
        $this->assertInstanceOf('\Connect\PhoneNumber', $request->asset->tiers->customer->contact_info->contact->phone_number);
        $this->assertEquals('123', $request->asset->tiers->customer->contact_info->contact->phone_number->area_code);
        $this->assertEquals('+1', $request->asset->tiers->customer->contact_info->contact->phone_number->country_code);
        $this->assertEquals('4567890', $request->asset->tiers->customer->contact_info->contact->phone_number->phone_number);
        $this->assertEquals('', $request->asset->tiers->customer->contact_info->contact->phone_number->extension);
    }

    public function testTierStructure()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.structure.json'));
        $request = new Request($requests[0]);
        $this->assertInstanceOf('Connect\Tier', $request->asset->tiers->tier1);
        $this->assertEquals('TA-0-2347-9392-6524', $request->asset->tiers->tier1->id);
        $this->assertEquals('ServicePro', $request->asset->tiers->tier1->name);
        $this->assertEquals('1', $request->asset->tiers->tier1->external_id);
        $this->assertInstanceOf('\Connect\ContactInfo', $request->asset->tiers->tier1->contact_info);
        $this->assertEquals('3351 Michelson Drive, Suite 100', $request->asset->tiers->tier1->contact_info->address_line1);
        $this->assertEquals('', $request->asset->tiers->tier1->contact_info->address_line2);
        $this->assertEquals('Irvine', $request->asset->tiers->tier1->contact_info->city);
        $this->assertEquals('us', $request->asset->tiers->tier1->contact_info->country);
        $this->assertEquals('CA', $request->asset->tiers->tier1->contact_info->state);
        $this->assertEquals('92612-0697', $request->asset->tiers->tier1->contact_info->postal_code);
        $this->assertInstanceOf('\Connect\Contact', $request->asset->tiers->tier1->contact_info->contact);
        $this->assertEquals('admin@imcdemos.com', $request->asset->tiers->tier1->contact_info->contact->email);
        $this->assertEquals('Charlie', $request->asset->tiers->tier1->contact_info->contact->first_name);
        $this->assertEquals('Smith', $request->asset->tiers->tier1->contact_info->contact->last_name);
        $this->assertInstanceOf('\Connect\PhoneNumber', $request->asset->tiers->tier1->contact_info->contact->phone_number);
        $this->assertEquals('714', $request->asset->tiers->tier1->contact_info->contact->phone_number->area_code);
        $this->assertEquals('+1', $request->asset->tiers->tier1->contact_info->contact->phone_number->country_code);
        $this->assertEquals('5661000', $request->asset->tiers->tier1->contact_info->contact->phone_number->phone_number);
        $this->assertEquals('', $request->asset->tiers->tier1->contact_info->contact->phone_number->extension);
    }
}
