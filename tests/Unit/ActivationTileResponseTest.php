<?php

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