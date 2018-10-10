<?php

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