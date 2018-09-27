<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

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
        foreach ($this->sessionLog as $r) {
            $log->write($r); // historical log dumped with actual event times
        }
        $log->write(new LogRecord(null, "=== Detailed session log dump end ====================", null));
    }
}