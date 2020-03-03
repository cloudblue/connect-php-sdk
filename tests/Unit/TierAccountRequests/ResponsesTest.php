<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\TierAccountRequestAccept;
use Connect\TierAccountRequestIgnore;

/**
 * Class TierAccountRequestAcceptTest
 * @package Tests\Unit
 */
class ResponsesTierAccountRequestsTest extends \Test\TestCase
{
    /**
     * @return mixed
     */
    public function testInstantiationAccept()
    {
        $accept = new TierAccountRequestAccept();

        $this->assertInstanceOf('\Connect\TierAccountRequestAccept', $accept);

        $this->assertEquals("TAR Processed", $accept->getMessage());
        $this->assertEquals('accepted', $accept->getCode());
        $this->assertEquals(null, $accept->getObject());
        return $accept;
    }

    /**
     * @return mixed
     */
    public function testInstantiationIgnore()
    {
        $ignore = new TierAccountRequestIgnore();

        $this->assertInstanceOf('\Connect\TierAccountRequestIgnore', $ignore);

        $this->assertEquals("This product can not process account change requests", $ignore->getMessage());
        $this->assertEquals('ignore', $ignore->getCode());
        $this->assertEquals(null, $ignore->getObject());
        return $ignore;
    }
}
