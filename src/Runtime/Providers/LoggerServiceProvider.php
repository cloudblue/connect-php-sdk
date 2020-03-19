<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Runtime\Providers;

use Connect\Config;
use Connect\Logger;
use Connect\Runtime\ServiceProvider;
use Pimple\Container;

/**
 * Class LoggerServiceProvider
 * @package Connect\Runtime\Providers
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
        /** @var Config $configuration */
        $configuration = $container['config'];

        $logger = Logger::get();
        $logger->setLogLevel($configuration->logLevel);

        return $logger;
    }
}
