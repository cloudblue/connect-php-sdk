<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


class ModelTest extends \Test\TestCase
{
    public function testInstantiation()
    {
        $model = new ModelHelper([
            'id' => 'T-JB-001',
            'username' => 'j.bourne',
            'password' => 'treadstone',
            'illegal' => 'thisisillegalvalue!',
            'isAdmin' => true,
        ]);

        $this->assertInstanceOf('\Connect\\Model', $model);

        $this->assertObjectHasAttribute('id', $model);
        $this->assertObjectHasAttribute('username', $model);
        $this->assertObjectHasAttribute('password', $model);
        $this->assertObjectHasAttribute('isAdmin', $model);

        $this->assertObjectNotHasAttribute('illegal', $model);

        $this->assertInternalType('array', $model->getHidden());
        $this->assertCount(3, $model->getHidden());

        $this->assertInternalType('array', $model->getProtected());
        $this->assertCount(1, $model->getProtected());

        $this->assertInternalType('array', $model->getRequired());
        $this->assertCount(3, $model->getRequired());
    }

    public function testRequiredFields()
    {
        $model = new ModelHelper([
            'id' => 'T-JB-001',
            'username' => 'j.bourne'
        ]);

        $this->assertInstanceOf('\Connect\\Model', $model);

        $missing = $model->validate();

        $this->assertInternalType('array', $missing);
        $this->assertCount(1, $missing);
        $this->assertEquals('password', $missing[0]);
    }

    public function testProtectedFields()
    {
        $model = new ModelHelper([
            'id' => 'T-JB-001',
            'username' => 'j.bourne',
            'password' => 'treadstone',
        ]);

        $this->assertInstanceOf('\Connect\\Model', $model);

        $protected = $model->__debugInfo();

        $this->assertInternalType('array', $protected);
        $this->assertCount(9, $protected);

        $this->assertEquals('T-JB-001', $protected['id']);
        $this->assertEquals('j.bourne', $protected['username']);
        $this->assertEquals('********************************', $protected['password']);
    }

    public function testGetters()
    {
        $model = new ModelHelper([
            'id' => 'T-JB-001',
            'username' => 'j.bourne',
            'password' => 'treadstone',
            'illegal' => 'thisisillegalvalue!',
            'isAdmin' => 'yes',
        ]);

        $this->assertEquals('T-JB-001', $model->get('id'));
        $this->assertEquals('j.bourne', $model->get('username'));
        $this->assertEquals('treadstone', $model->get('password'));
        $this->assertEquals('yes', $model->get('isAdmin'));

        $this->assertEquals('yes', $model->isAdmin);

        $this->assertNull($model->get('wrong_property'));
    }

    public function testHydrate()
    {
        $model = new ModelHelper();
        $model->hydrate([
            'id' => 'T-JB-001',
            'username' => 'j.bourne',
            'password' => 'treadstone',
        ]);

        $this->assertInstanceOf('\Connect\\Model', $model);

        $this->assertEquals('T-JB-001', $model->id);
        $this->assertEquals('j.bourne', $model->username);
        $this->assertEquals('treadstone', $model->password);
    }

    public function testHydrateComplexStructures()
    {
        $source = json_decode('{"connection": {
          "id": "CT-0000-0000-0000",
          "provider": {
            "id": "PA-473-705",
            "name": "ACME Provider"
          },
          "type": "preview",
          "vendor": {
            "id": "VA-691-879",
            "name": "Marc FSG"
          }
        }}');

        $model = new ModelHelper($source);

        $this->assertInstanceOf('\Connect\\Connection', $model->connection);
        $this->assertInstanceOf('\Connect\\Provider', $model->connection->provider);
        $this->assertInstanceOf('\Connect\\Vendor', $model->connection->vendor);
    }

    public function testHydrateComplexStructuresWithIndex()
    {
        $source = json_decode('{"tiers": {
          "tier1": {
            "contact_info": {
              "address_line1": "noname",
              "address_line2": "",
              "city": "noname",
              "contact": {
                "email": "no-reply@acme.example.com",
                "first_name": "ACME",
                "last_name": "Reseller",
                "phone_number": {
                  "area_code": "234",
                  "country_code": "+1",
                  "extension": "",
                  "phone_number": "567890"
                }
              },
              "country": "us",
              "postal_code": "12111",
              "state": "Alaska"
            },
            "external_id": 1,
            "id": "TA-0-7251-3930-7482",
            "name": "ACME Reseller"
          }
        }}');

        $model = new ModelHelper($source);

        $this->assertInstanceOf('\Connect\\Tiers', $model->tiers);
        $this->assertInstanceOf('\Connect\\Tier', $model->tiers->tier1);
    }

    public function testHydrateCollections()
    {
        $source = json_decode('{"items": [
          {
            "id": "TEAM_ST3L2T1Y",
            "mpn": "TEAM-ST3L2T1Y",
            "old_quantity": "0",
            "quantity": "100"
          },
          {
            "id": "TEAM_ST3L2TAC1M",
            "mpn": "TEAM-ST3L2TAC1M",
            "old_quantity": "0",
            "quantity": "200"
          }
        ]}');

        $model = new ModelHelper($source);

        $this->assertInternalType('array', $model->items);
        $this->assertCount(2, $model->items);

        foreach ($model->items as $items) {
            $this->assertInstanceOf('\Connect\\Item', $items);
        }
    }

    public function testHydrateCollectionsCompositeNames()
    {
        $source = json_decode('{"value_choices": [
          {
            "label": "Really Good",
            "value": "reallygood"
          },
          {
            "label": "Good",
            "value": "Good"
          },
          {
            "label": "Don\'t ask",
            "value": "bad"
          }
        ]}');

        $model = new ModelHelper($source);

        $this->assertInternalType('array', $model->value_choices);
        $this->assertCount(3, $model->value_choices);

        foreach ($model->value_choices as $value_choice) {
            $this->assertInstanceOf('\Connect\\ValueChoice', $value_choice);
        }
    }

    public function testHydrateCollectionsRandomModels()
    {
        $source = json_decode('{"vectors": [
          {
            "x": 1,
            "y": 1
          },
          {
            "x": 2,
            "y": 2
          },
          {
            "x": 3,
            "y": 3
          }
        ]}');

        $model = new ModelHelper($source);

        $this->assertInternalType('array', $model->vectors);
        $this->assertCount(3, $model->vectors);

        foreach ($model->vectors as $vectors) {
            $this->assertInstanceOf('\Connect\\Model', $vectors);
        }
    }

    public function testToArray()
    {
        $source = json_decode('{
          "id": "PR-5620-6510-8214",
          "username": "j.bourne",
          "password": "treadstone",
          "items": [
            {
              "id": "TEAM_ST3L2T1Y",
              "mpn": "TEAM-ST3L2T1Y",
              "old_quantity": "0",
              "quantity": "100"
            },
            {
              "id": "TEAM_ST3L2TAC1M",
              "mpn": "TEAM-ST3L2TAC1M",
              "old_quantity": "0",
              "quantity": "200"
            }
          ],
          "tiers": {
            "tier1": {
              "contact_info": {
                "address_line1": "noname",
                "address_line2": "",
                "city": "noname",
                "contact": {
                  "email": "no-reply@acme.example.com",
                  "first_name": "ACME",
                  "last_name": "Reseller",
                  "phone_number": {
                    "area_code": "234",
                    "country_code": "+1",
                    "extension": "",
                    "phone_number": "567890"
                  }
                },
                "country": "us",
                "postal_code": "12111",
                "state": "Alaska"
              },
              "external_id": 1,
              "id": "TA-0-7251-3930-7482",
              "name": "ACME Reseller"
            }
          }
        }');

        $model = new ModelHelper($source);
        $array = $model->toArray();

        $this->assertInternalType('array', $array);
        $this->assertCount(5, $array);

        $this->assertInternalType('array', $array['items']);
        $this->assertCount(2, $array['items']);
        foreach ($array['items'] as $item) {
            $this->assertInternalType('array', $item);
            $this->assertCount(4, $item);
        }

        $this->assertInternalType('array', $array['tiers']);
        $this->assertCount(1, $array['tiers']);

        $this->assertInternalType('array', $array['tiers']['tier1']);
        $this->assertCount(4, $array['tiers']['tier1']);

        $this->assertInternalType('array', $array['tiers']['tier1']['contact_info']);
        $this->assertCount(7, $array['tiers']['tier1']['contact_info']);

    }

    public function testToJson()
    {
        $model = new ModelHelper([
            'id' => 'T-JB-001',
            'username' => 'j.bourne',
            'password' => 'treadstone',
        ]);

        $this->assertEquals('{"id":"T-JB-001","username":"j.bourne","password":"treadstone"}', $model->toJSON());
    }

}