<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Logger;

/**
 * Interface for Logger
 *
 * @package Connect
 */
interface LoggerInterface extends \Psr\Log\LoggerInterface
{
    const LEVEL_TRACE = 4;
    const LEVEL_DEBUG = 3;
    const LEVEL_INFO = 2;
    const LEVEL_ERROR = 1;
    const LEVEL_FATAL = 0;

    const LEVELS = array(
        self::LEVEL_FATAL => 'FATAL',
        self::LEVEL_ERROR => 'ERROR',
        self::LEVEL_INFO => 'INFO',
        self::LEVEL_DEBUG => 'DEBUG',
        self::LEVEL_TRACE => 'TRACE'
    );

    /**
     * Logs trace messages
     *
     * @param string $message
     */
    public function trace($message);

    /**
     * Logs debug messages
     *
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context = []);

    /**
     * Logs info messages
     *
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = []);

    /**
     * Logs error messages
     *
     * @param string $message
     * @param array $context
     */
    public function error($message, array $context = []);

    /**
     * Logs fatal messages
     *
     * @param string $message
     */
    public function fatal($message);

    /**
     * Logs message of any level
     *
     * @param int $level - one of LEVEL_* constants
     * @param string $message - message to log
     * @param array $context
     */
    public function log($level, $message, array $context = []);

    /**
     * Write log record into log
     *
     * @param LogRecord $record
     */
    public function write(LogRecord $record);

    /**
     * Set logger logLevel (one of LEVEL_* constants)
     * @param int $level
     */
    public function setLogLevel($level);

    /**
     * Dump session log to current log, and reset session
     */
    public function dump();
}