<?php

namespace Tests\Unit;


use Connect\Message;

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