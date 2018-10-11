<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Connect\Logger\LoggerInterface;
use Connect\Logger\LogRecord;
use Connect\Logger\LogSession;

/**
 * Default Logger for Cloud Blue Connect SDK.
 *
 * @package Connect
 */
class Logger implements LoggerInterface
{
    protected static $instance;

    protected $logFile;
    protected $fp;
    protected $logLevel = self::LEVEL_INFO;
    protected $session;

    public function __construct()
    {
        if (!ini_get('date.timezone')) {
            ini_set('date.timezone', 'UTC');
        }

        if (defined('CONNECT_DEBUG') || isset($_SERVER['CONNECT_DEBUG'])) {
            $this->setLogLevel(self::LEVEL_TRACE);
        }

        $this->session = new LogSession();
    }

    /**
     * Set logger logLevel (one of LEVEL_* constants)
     * @param int $level
     * @return $this
     */
    public function setLogLevel($level)
    {
        $this->logLevel = $level;
        return $this;
    }

    /**
     * Set the log file path
     * @param $filePath
     * @throws Exception
     * @return $this
     */
    public function setLogFile($filePath)
    {
        if ($this->logFile != $filePath && $this->fp) {
            @fclose($this->fp);
            $this->fp = null;
        }
        $logDir = dirname($filePath);
        if (!file_exists($logDir)) {
            @mkdir($logDir, 0755, true);
        }

        if (!file_exists($logDir)) {
            throw new Exception(sprintf('Can\'t create log directory "%s".', $logDir), 'logger');
        }

        if (!$this->fp = @fopen($filePath, 'a+')) {
            throw new Exception(sprintf('Can\'t create log file "%s".', $filePath), 'logger');
        }

        $this->logFile = $filePath;
        return $this;
    }

    /**
     * Initialize Logger instance
     */
    public static function init()
    {
        self::get();
    }

    /**
     * @param $message
     */
    public function trace($message)
    {
        $this->log(self::LEVEL_TRACE, $message);
    }

    /**
     * @param $message
     * @param array $context
     */
    public function debug($message, array $context = [])
    {
        $this->log(self::LEVEL_DEBUG, $message);
    }

    /**
     * @param $message
     * @param array $context
     */
    public function info($message, array $context = [])
    {
        $this->log(self::LEVEL_INFO, $message);
    }

    /**
     * @param $message
     * @param array $context
     */
    public function error($message, array $context = [])
    {
        $this->log(self::LEVEL_ERROR, $message);
    }

    /**
     * @param $message
     */
    public function fatal($message)
    {
        $this->log(self::LEVEL_FATAL, $message);
    }

    /**
     * Added a log record at the EMERGENCY level.
     * @param string $message
     * @param mixed $context
     * @return string
     */
    public function emergency($message, array $context = array())
    {
        $this->log(self::LEVEL_FATAL, $message, $context);
    }

    /**
     * Added a log record at the ALERT level.
     * @param string $message
     * @param array $context
     * @return string
     */
    public function alert($message, array $context = array())
    {
        $this->log(self::LEVEL_FATAL, $message, $context);
    }

    /**
     * Added a log record at the CRITICAL level.
     * @param string $message
     * @param array $context
     * @return string
     */
    public function critical($message, array $context = array())
    {
        $this->log(self::LEVEL_FATAL, $message, $context);
    }

    /**
     * Added a log record at the WARNING level.
     * @param string $message
     * @param array $context
     * @return string
     */
    public function warning($message, array $context = array())
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    /**
     * Added a log record at the NOTICE level.
     * @param string $message
     * @param array $context
     * @return string
     */
    public function notice($message, array $context = array())
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    /**
     * Log message of any level
     *
     * @param int $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $record = new LogRecord($level, $message);

        // add all messages to session log
        $this->session->addRecord($record);

        // write record into log only if it is enough by level
        if ($this->logLevel >= $level) {
            $this->write($record);
        }
    }

    /**
     * Write log record into log
     *
     * @param LogRecord $record
     * @return string
     */
    public function write(LogRecord $record)
    {
        $logLine = array();

        if ($record->time) {
            $timestr = ($this->logLevel >= self::LEVEL_DEBUG) ?
                $this->udate('Y-m-d H:i:s.u T', $record->time) :
                date('Y/m/d h:i:s', $record->time);
            $logLine[] = "[ $timestr ]";
        }

        if ($record->level != null) {
            $logLine[] = self::LEVELS[$record->level];
        }

        $logLine[] = $record->message;

        $message = implode(' ', $logLine) . PHP_EOL;

        if ($this->fp) {
            fwrite($this->fp, $message);
        } else {
            if (isset($GLOBALS['useErrorLog'])) {
                error_log($message);
            } else {
                file_put_contents('php://stderr', $message, FILE_APPEND);
            }
        }

        return $message;
    }

    /**
     * @param string $format
     * @param null $utimestamp
     *
     * @return bool|string
     */
    private function udate($format = 'u', $utimestamp = null)
    {
        $utimestamp = is_null($utimestamp) ? microtime(true) : $utimestamp;
        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }

    /**
     * Set global instance of LoggerInterface to be used
     * by Cloud Blue Connect SDK itself.
     *
     * @param LoggerInterface $logger
     */
    public static function set($logger)
    {
        self::$instance = $logger;
    }

    /**
     * Retrieve global instance of LoggerInterface to be used by Cloud Blue Connect SDK
     * itself. Returns instance of class Logger if none was set before.
     *
     * @return LoggerInterface global object.
     */
    public static function get()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Dump session log to current log, and reset session
     * @return $this
     */
    public function dump()
    {
        $this->session->dumpTo($this);
        $this->session->reset();
        return $this;
    }

    public function __destruct()
    {
        if ($this->fp) {
            @fclose($this->fp);
        }
    }
}
