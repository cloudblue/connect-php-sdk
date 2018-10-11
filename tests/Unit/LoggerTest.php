<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Logger;

class LoggerTest extends \Test\TestCase
{

    public function testInstantiation()
    {
        @ini_set('date.timezone', null);

        $logger = new LoggerHelper();
        $this->assertInstanceOf('\Connect\Logger', $logger);

        $this->assertEquals(2, $logger->getLogLevel());
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstantiationDebugConstant()
    {
        define('CONNECT_DEBUG', true);

        $logger = new LoggerHelper();
        $this->assertInstanceOf('\Connect\Logger', $logger);

        $this->assertEquals(4, $logger->getLogLevel());
    }

    /**
     * @runInSeparateProcess
     */
    public function testInstantiationDebugServerEnv()
    {
        $_SERVER['CONNECT_DEBUG'] = true;

        $logger = new LoggerHelper();
        $this->assertInstanceOf('\Connect\Logger', $logger);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetSingleton()
    {
        $i1 = \Connect\Logger::get();
        $this->assertInstanceOf('\Connect\Logger', $i1);

        $i2 = \Connect\Logger::get();
        $this->assertInstanceOf('\Connect\Logger', $i2);

        $this->assertEquals(spl_object_hash($i1), spl_object_hash($i2));
    }

    /**
     * @runInSeparateProcess
     */
    public function testInit()
    {
        $this->assertNull(\Connect\Logger::init());

        $i1 = \Connect\Logger::get();
        $this->assertInstanceOf('\Connect\Logger', $i1);

    }

    /**
     * @runInSeparateProcess
     */
    public function testSetSingleton()
    {
        $i1 = new Logger();
        $this->assertInstanceOf('\Connect\Logger', $i1);

        \Connect\Logger::set($i1);

        $i2 = \Connect\Logger::get();
        $this->assertInstanceOf('\Connect\Logger', $i2);

        $this->assertEquals(spl_object_hash($i1), spl_object_hash($i2));
    }

    public function testSetLogFile()
    {
        $files = [
            'logFile' => __DIR__ . '/logs/test.log',
            'logFileOverride' => __DIR__ . '/logs/testOverride.log'
        ];

        foreach ($files as $file => $path) {
            if (is_readable($path)) {
                unlink($path);
            }
        }

        $logger = new LoggerHelper();

        $logger->setLogFile($files['logFile']);

        $this->assertFileExists($files['logFile']);
        $this->assertEquals($logger->getLogFile(), $files['logFile']);

        $logger->setLogFile($files['logFileOverride']);

        $this->assertFileExists($files['logFileOverride']);
        $this->assertEquals($logger->getLogFile(), $files['logFileOverride']);

        foreach ($files as $file => $path) {
            if (is_readable($path)) {
                unlink($path);
            }
        }
    }

    public function testWrite()
    {
        $i1 = new Logger();

        $time = time();

        $msg = $i1->write(new Logger\LogRecord(1, 'This is a msg', $time));
        $expected = sprintf("[ %s ] ERROR %s\n", date('Y/m/d h:i:s', $time), 'This is a msg');

        $this->assertEquals($expected, $msg);
    }

    public function testWriteDebug()
    {
        $_SERVER['CONNECT_DEBUG'] = true;

        $i1 = new Logger();

        $utimestamp = time();

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);
        $debugTime = date(preg_replace('`(?<!\\\\)u`', $milliseconds, 'Y-m-d H:i:s.u T'), $timestamp);

        $msg = $i1->write(new Logger\LogRecord(1, 'This is a msg', $utimestamp));

        $expected = sprintf("[ %s ] ERROR %s\n", $debugTime, 'This is a msg');

        $this->assertEquals($expected, $msg);
    }

    public function testWriteToErrorLog()
    {
        $GLOBALS['useErrorLog'] = true;

        $i1 = new Logger();

        $time = time();

        $msg = $i1->write(new Logger\LogRecord(1, 'This is a msg', $time));
        $expected = sprintf("[ %s ] ERROR %s\n", date('Y/m/d h:i:s', $time), 'This is a msg');

        $this->assertEquals($expected, $msg);
    }

    public function testWriteToFile()
    {
        $logFile = __DIR__ . '/test.log';

        if (is_readable($logFile)) {
            unlink($logFile);
        }

        $logger = new LoggerHelper();
        $logger->setLogFile($logFile);

        $this->assertFileExists($logFile);
        $this->assertEquals(0, filesize($logFile));

        $time = time();
        $msg = $logger->write(new Logger\LogRecord(1, 'This is a msg', $time));
        $expected = sprintf("[ %s ] ERROR %s\n", date('Y/m/d h:i:s', $time), 'This is a msg');

        $this->assertEquals($expected, $msg);
        $this->assertEquals($expected, file_get_contents($logFile));

        if (is_readable($logFile)) {
            unlink($logFile);
        }
    }

    /**
     * @throws \Connect\Exception
     *
     * @expectedException \Exception
     */
    public function testSetLogFileFailDirectoryCreation()
    {
        $logger = new LoggerHelper();
        $logger->setLogFile('/root/null/lol');
    }

    /**
     * @throws \Connect\Exception
     *
     * @expectedException \Exception
     */
    public function testSetLogFileFailFileCreation()
    {
        $logger = new LoggerHelper();
        $logger->setLogFile('/root/test.log');
    }

    public function testLogLevels()
    {
        $logger = new LoggerHelper();

        $levels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug', 'trace', 'fatal'];
        foreach ($levels as $level) {
            $this->assertNull($logger->{$level}('Some cool message'));
        }

        $this->assertCount(10, $logger->getSession());

        return $logger;
    }

    /**
     * @param LoggerHelper $logger
     *
     * @depends testLogLevels
     */
    public function testDump(LoggerHelper $logger)
    {
        $this->assertInstanceOf('\Connect\Logger', $logger->dump());
        $this->assertCount(0, $logger->getSession());

    }

}