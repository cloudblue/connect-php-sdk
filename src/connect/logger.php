<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Interface for Logger
 *
 * @package Connect
 */
interface LoggerInterface
{
	const LEVEL_TRACE = 4;
	const LEVEL_DEBUG = 3;
	const LEVEL_INFO  = 2;
	const LEVEL_ERROR = 1;
	const LEVEL_FATAL = 0;

	const LEVELS = array(
			self::LEVEL_FATAL => 'FATAL',
			self::LEVEL_ERROR => 'ERROR',
			self::LEVEL_INFO  => 'INFO',
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

    /**
     * Logs message of any level
     *
     * @param int    $level   - one of LEVEL_* constants
     * @param string $message - message to log
     */
    public function log($level, $message);

    /**
     * Write log record into log
     *
     * @param LogRecord $record
     */
    public function write($record);

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

/**
 * LogRecord class, instance created for every logging action
 *
 * @package Connect
 */
class LogRecord
{
	public $level;
	public $message;
	public $time;

	public function __construct($level, $message, $time = 0)
	{
		$this->level = $level;
		$this->message = $message;
		$this->time = ($time === 0) ? time() : $time;
	}
}

/**
 * Collects all log records for one processing batch
 * In case of exception these records will be added to log for analysis
 *
 * @package Connect
 */
class LogSession
{
	private $sessionLog = array();

	/**
	 * Add logging record to session log
	 *
	 * @param LogRecord $record
	 */
	public function addRecord($record)
	{
		$this->sessionLog[] = $record;
	}

	/**
	 * Reset session log
	 */
	public function reset()
	{
		$this->sessionLog = array();
	}

	/**
	 * Dump session log to specified logger
	 *
	 * @param LoggerInterface $log
	 */
	public function dumpTo($log)
	{
		if (!$this->sessionLog)
			return;

		$log->write(new LogRecord(null, "=== Detailed session log dump begin ==================", null));
		foreach($this->sessionLog as $r) {
			$log->write($r); // historical log dumped with actual event times
		}
		$log->write(new LogRecord(null, "=== Detailed session log dump end ====================", null));
	}
}

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
			$this->logLevel = self::LEVEL_TRACE;
        }

        $this->session = new LogSession();
    }

    function __destruct()
    {
        if ($this->fp) {
            @fclose($this->fp);
        }
    }

    /**
     * Set logger logLevel (one of LEVEL_* constants)
     * @param int $level
     */
    public function setLogLevel($level)
    {
		$this->logLevel = $level;
    }

    /**
     * @param $filePath
     *
     * @throws Exception
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
                throw new Exception(
                    sprintf('Can\'t create log directory "%s".', $logDir),
                    'logger'
                );
            }
        }

        if (!$this->fp = fopen($filePath, 'a+')) {
            throw new Exception(
                sprintf('Can\'t create log file "%s".', $filePath),
                'logger'
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
		$this->log(self::LEVEL_TRACE, $message);
    }

    /**
     * @param $message
     */
    public function debug($message)
    {
		$this->log(self::LEVEL_DEBUG, $message);
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
     * Log message of any level
     *
     * @param int 	 $level
     * @param string $message
     */
    public function log($level, $message)
    {
		$record = new LogRecord($level, $message);

		// add all messages to session log
		$this->session->addRecord($record);    	

		// write record into log only if it is enough by level
		if ($this->logLevel >= $level)
		$this->write($record);
    }

    /**
     * Write log record into log
     * 
     * @param LogRecord $record
     */
    public function write($record)
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
    
    /**
     * Dump session log to current log, and reset session
     */
    public function dump()
    {
		$this->session->dumpTo($this);
		$this->session->reset();
    }
}
