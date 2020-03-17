<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\ActivationTileResponse;

/**
 * Class ActivationTileResponseTest
 * @package Tests\Unit
 */
class ActivationTileResponseTest extends \Test\TestCase
{

    /**
     * @return ActivationTileResponse
     */
    public function testInstantiation()
    {
        $responseMessage = new ActivationTileResponse('SOME MESSAGE');

        $this->assertInstanceOf('\Connect\ActivationTileResponse', $responseMessage);

        $this->assertEquals("SOME MESSAGE", $responseMessage->activationTile);
        return $responseMessage;
    }
}
