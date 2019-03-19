<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Logger;

/**
 * Collects all log records for one processing batch
 * In case of exception these records will be added to log for analysis
 * @package Connect
 */
class LogSession implements \Countable
{
    /**
     * Collection of LogRecords
     * @var LogRecord[]
     */
    private $sessionLog = [];

    /**
     * Add logging record to session log
     * @param LogRecord $record
     * @return $this
     */
    public function addRecord(LogRecord $record)
    {
        $this->sessionLog[] = $record;
        return $this;
    }

    /**
     * Reset session log
     * @return $this
     */
    public function reset()
    {
        $this->sessionLog = [];
        return $this;
    }

    /**
     * Dump session log to specified logger
     * @param LoggerInterface $logger
     * @return $this
     */
    public function dumpTo(LoggerInterface $logger)
    {
        if (empty($this->sessionLog)) {
            return $this;
        }

        $logger->write(new LogRecord(null, "=== Detailed session log dump begin ==================", null));
        foreach ($this->sessionLog as $record) {
            $logger->write($record); // historical log dumped with actual event times
        }
        $logger->write(new LogRecord(null, "=== Detailed session log dump end ====================", null));

        return $this;
    }

    /**
     * Count the LogRecords
     * @return int
     */
    public function count()
    {
        return count($this->sessionLog);
    }
}
