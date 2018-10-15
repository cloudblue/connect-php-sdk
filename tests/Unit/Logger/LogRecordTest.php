<?php

namespace Test\Unit\Logger;


use Connect\Logger\LogRecord;

/**
 * Class LogRecordTest
 * @package Test\Unit\Logger
 */
class LogRecordTest extends \Test\TestCase
{
    public function testInstantiation()
    {
        $time = time();
        $record = new LogRecord(1, 'This is a msg', $time);
        $this->assertInstanceOf('\Connect\Logger\LogRecord', $record);

        $this->assertEquals(1, $record->level);
        $this->assertEquals('This is a msg', $record->message);
        $this->assertEquals($time, $record->time);
    }
}