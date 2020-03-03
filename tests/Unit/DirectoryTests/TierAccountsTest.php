<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\DirectoryTests;

use Connect\Config;
use Connect\ConnectClient;
use Connect\RQL\Query;

class TierAccountsTest extends \Test\TestCase
{
    public function testGetTierAccounts()
    {
        $connectClient = new ConnectClient(new Config(__DIR__ . '/config.mocked.getTierAccounts.json'));
        $accounts = $connectClient->directory->listTierAccounts();
        foreach ($accounts as $account) {
            $this->assertInstanceOf("\Connect\TierAccount", $account);
        }
    }

    public function testGetTierAccountsRQL()
    {
        $connectClient = new ConnectClient(new Config(__DIR__ . '/config.mocked.getTierAccounts.json'));
        $accounts = $connectClient->directory->listTierAccounts(new Query(['limit' => 3]));
        foreach ($accounts as $account) {
            $this->assertInstanceOf("\Connect\TierAccount", $account);
        }
    }

    public function testGetTierAccountsArray()
    {
        $connectClient = new ConnectClient(new Config(__DIR__ . '/config.mocked.getTierAccounts.json'));
        $accounts = $connectClient->directory->listTierAccounts(['limit' => 3]);
        foreach ($accounts as $account) {
            $this->assertInstanceOf("\Connect\TierAccount", $account);
        }
        $this->assertInstanceOf("\Connect\TierAccount", $connectClient->directory->getTierAccountById($account->id));
    }

}