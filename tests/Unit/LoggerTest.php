<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use \Connect\Logger;

class LoggerTest extends \Test\TestCase
{
    public function testConstructor()
    {
        define('CONNECT_DEBUG', true);
        $logger = new Logger();

        $this->assertInstanceOf('\Connect\Logger', $logger);

        return $logger;
    }

}