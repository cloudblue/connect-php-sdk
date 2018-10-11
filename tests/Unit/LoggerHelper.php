<?php

namespace Test\Unit;

/**
 * Class LoggerHelper
 * @package Test\Unit
 */
class LoggerHelper extends \Connect\Logger
{
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    public function getLogFile()
    {
        return $this->logFile;
    }

    public function getStream()
    {
        return $this->fp;
    }

    public function getSession()
    {
        return $this->session;
    }
}