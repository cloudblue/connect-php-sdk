<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\TierAccountRequest;
use Connect\TierAccountRequestAccept;
use Connect\TierAccountRequestIgnore;
use Connect\TierAccountRequestsAutomation;

/**
 * Class TierAccountRequestsBasicsHelper
 * @package Test\Unit
 */
class TierAccountRequestsBasicsHelper extends TierAccountRequestsAutomation
{

    /**
     * @inheritDoc
     */
    public function processTierAccountRequest(\Connect\TierAccountRequest $request)
    {
        switch ($request->id) {
            case 'TAR-2709-2353-6222-008-001':
                $request->account->diffWithPreviousVersion();
                throw new TierAccountRequestAccept("YES");
            case 'TAR-2709-2353-6222-008-002':
                $request->account->diffWithPreviousVersion($request->account->version -1);
                throw new TierAccountRequestIgnore("NO WAY");
            case 'TAR-2709-2353-6222-008-004':
                return "This will not work and will be skiped";
            case 'TAR-2709-2353-6222-008-005':
                $object = new \stdClass();
                $object->message = "this will not work and will be skipped";
                return $object;
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getHttp()
    {
        return $this->http;
    }

    public function getStd()
    {
        return $this->std;
    }
}
