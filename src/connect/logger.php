<?php
/**
 * This file is part of the Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Cloud Blue. All Rights Reserved.
 */

namespace Connect;

/**
 * Interface for Logger
 *
 * @package Connect
 */
interface LoggerInterface
{
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
     */
    public function debug($message);

    /**
     * Logs info messages
     *
     * @param string $message
     */
    public function info($message);

    /**
     * Logs error messages
     *
     * @param string $message
     */
    public function error($message);

    /**
     * Logs fatal messages
     *
     * @param string $message
     */
    public function fatal($message);
}

 /**
 * Default Logger for Cloud Blue Connect SDK.
 *
 * @package Connect
 */
class Logger implements LoggerInterface
{
    const LEVEL_TRACE = 'TRACE';
    const LEVEL_DEBUG = 'DEBUG';
    const LEVEL_INFO = 'INFO';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_FATAL = 'FATAL';

    protected static $instance;

    protected $logFile;
    protected $fp;
    protected $debug = false;

    public function __construct()
    {
        if (!ini_get('date.timezone')) {
            ini_set('date.timezone', 'UTC');
        }

        if (defined('CONNECT_DEBUG') || isset($_SERVER['CONNECT_DEBUG'])) {
        	$this->debug = true;
        }
    }

    function __destruct()
    {
        if ($this->fp) {
            @fclose($this->fp);
        }
    }

    /**
     * @param $filePath
     *
     * @throws \Exception
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
            if (!file_exists($logDir)) {
                throw new \Exception(
                    sprintf('Can\'t create log directory "%s".', $logDir)
                );
            }
        }

        if (!$this->fp = fopen($filePath, 'a+')) {
            throw new \Exception(
                sprintf('Can\'t create log file "%s".', $filePath)
            );
        }
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
        if ($this->debug) {
            $this->log(self::LEVEL_TRACE, $message);
        }
    }

    /**
     * @param $message
     */
    public function debug($message)
    {
        if ($this->debug) {
            $this->log(self::LEVEL_DEBUG, $message);
        }
    }

    /**
     * @param $message
     */
    public function info($message)
    {
        $this->log(self::LEVEL_INFO, $message);
    }

    /**
     * @param $message
     */
    public function error($message)
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
     * @param $level
     * @param $message
     */
    protected function log($level, $message)
    {
        if ($this->debug) {
            // recording milliseconds in dev mode
            $message = '[' . $this->udate('Y-m-d H:i:s.u T') . "] $level " . $message . PHP_EOL;
        } else {
            $message = '[' . date('Y/m/d h:i:s') . "] $level " . $message . PHP_EOL;
        }

        if ($this->fp) {
            fwrite($this->fp, $message);
        } else {
            if (isset($GLOBALS['useErrorLog'])) {
                error_log($message);
            } else {
                file_put_contents('php://stderr', $message, FILE_APPEND);
            }
        }
    }

    /**
     * @param string $format
     * @param null $utimestamp
     *
     * @return bool|string
     */
    private function udate($format = 'u', $utimestamp = null)
    {
        if (is_null($utimestamp)) {
            $utimestamp = microtime(true);
        }

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
    
}
