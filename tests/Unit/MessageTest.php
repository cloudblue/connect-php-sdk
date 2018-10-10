<?php

namespace Test\Unit;


use Connect\Message;

/**
 * Class MessageTest
 * @package Tests\Unit
 */
class MessageTest extends \Test\TestCase
{
    /**
     * @return Message
     */
    public function testInstantiation()
    {
        $message = new Message("Test", 404, null);

        $this->assertInstanceOf('\Connect\Message', $message);

        $this->assertEquals("Test", $message->getMessage());
        $this->assertEquals('404', $message->getCode());
        $this->assertEquals(null, $message->getObject());
        return $message;
    }

}