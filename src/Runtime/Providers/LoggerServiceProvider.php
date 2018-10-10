<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Runtime\Providers;

use Connect\Logger;
use Connect\Runtime\ServiceProvider;
use Pimple\Container;

/**
 * Class LoggerProvider
 * @package Connect\RuntimeProviders
 */
class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Create the Logger Service
     * @param Container $container
     * @return \Psr\Log\LoggerInterface
     */
    public function register(Container $container)
    {
        $logger = Logger::get();
        $logger->setLogLevel($container['config']->logLevel);

        return $logger;
    }
}