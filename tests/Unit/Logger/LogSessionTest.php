<?php

namespace Test\Unit\Logger;

use Connect\Logger\LogRecord;
use Connect\Logger\LogSession;

/**
 * Class LogRecordTest
 * @package Test\Unit\Logger
 */
class LogSessionTest extends \Test\TestCase
{
    /**
     * @return LogSession
     */
    public function testInstantiationAndAddRecord()
    {
        $session = new LogSession();
        $this->assertInstanceOf('\Connect\Logger\LogSession', $session);

        $this->assertCount(0, $session);
        $session->addRecord(new LogRecord(1, 'msg1'));
        $this->assertCount(1, $session);

        return $session;
    }

    /**
     * @param LogSession $session
     * @return LogSession
     *
     * @depends testInstantiationAndAddRecord
     */
    public function testResetSession(LogSession $session)
    {
        $this->assertInstanceOf('\Connect\Logger\LogSession', $session->reset());
        $this->assertCount(0, $session);

        return $session;
    }

    /**
     * @param LogSession $session
     *
     * @depends testInstantiationAndAddRecord
     */
    public function testDumpTo(LogSession $session)
    {
        $logger = \Mockery::mock('\Connect\Logger\LoggerInterface');
        $logger->shouldReceive('write')
            ->times(3)
            ->withAnyArgs();

        $this->assertInstanceOf('\Connect\Logger\LogSession', $session->dumpTo($logger));
        $this->assertCount(0, $session);

        $session->addRecord(new LogRecord(1, 'msg1'));
        $this->assertCount(1, $session);
        $this->assertInstanceOf('\Connect\Logger\LogSession', $session->dumpTo($logger));
        $this->assertCount(1, $session);
    }
}
