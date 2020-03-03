<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Config;
use Connect\RQL\Query;
use Connect\TierAccountRequest;
use Connect\TierAccountRequestsAutomation;

require_once __DIR__."/TierAccountRequestsBasicsHelper.php";

class TierAccountRequestsBasicsTest extends \Test\TestCase
{
    protected function setUp()
    {
        /**
         * change the work dir, by default the default config file
         * must be in the same directory of the entry point.
         */
        chdir(__DIR__);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCommonUseCases()
    {
        $app = new TierAccountRequestsBasicsHelper(new Config('./config.mocked.json'));
        $this->assertInstanceOf('\Connect\TierAccountRequestsAutomation', $app);
        $app->process();
    }

    public function testListTARsWithFiltersArray()
    {
        $client = new \Connect\ConnectClient(new Config('./config.mocked.json'));
        $requests = $client->directory->listTierAccountRequests(['status' => 'pending']);
        foreach ($requests as $request) {
            $this->assertInstanceOf('\Connect\TierAccountRequest', $request);
        }
    }

    public function testListTARsWithFiltersRQL()
    {
        $client = new \Connect\ConnectClient(new Config('./config.mocked.json'));
        $requests = $client->directory->listTierAccountRequests(new Query(['status' => 'pending']));
        foreach ($requests as $request) {
            $this->assertInstanceOf('\Connect\TierAccountRequest', $request);
        }
    }

    public function testListTARsWithoutFilters()
    {
        $client = new \Connect\ConnectClient(new Config('./config.mocked.json'));
        $requests = $client->directory->listTierAccountRequests();
        foreach ($requests as $request) {
            $this->assertInstanceOf('\Connect\TierAccountRequest', $request);
        }
    }

    public function testCreateTierAccountRequestChange()
    {
        $client = new \Connect\ConnectClient(new Config('./config.creation.mocked.json'));
        $tar = new TierAccountRequest([
            "type" => "update",
            "account" => [
                "id" => "TA-2709-2353-6222",
                "external_uid" => "2f706f59-d0fe-493f-be4c-63059ea81bd2",
                "name" => "string4",
                "contact_info" => [
                    "address_line1" => "120354 Main street",
                    "address_line2" => "",
                    "city" => "Springfield",
                    "state" => "string",
                    "postal_code" => "10500",
                    "country" => "tr",
                    "contact" => [
                        "email" => "xandercage@xxx.net",
                        "first_name" => "Xander",
                        "last_name" => "Cage",
                        "phone_number" => [
                            "area_code" => "1",
                            "country_code" => "+252",
                            "extension" => "",
                            "phone_number" => "017136"
                        ]
                    ]
                ]
            ]
        ]);
        $responses = $client->directory->createTierAccountRequest($tar);
        foreach ($responses as $response) {
            $this->assertInstanceOf('\Connect\TierAccountRequest', $response);
        }
    }
}
