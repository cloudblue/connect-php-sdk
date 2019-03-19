<?php

namespace Test\Unit\Runtime\Providers;

use Connect\Config;
use Connect\Logger;
use Connect\Runtime\ServiceProvider;
use Pimple\Container;

/**
 * Class LoggerProvider
 * @package Connect\RuntimeProviders
 */
class LoggerServiceProvider extends ServiceProvider
{
    public function register(Container $container)
    {
        $logger = \Mockery::mock('LoggerInterface');

        $logger->shouldReceive('emergency')->withAnyArgs();
        $logger->shouldReceive('alert')->withAnyArgs();
        $logger->shouldReceive('critical')->withAnyArgs();
        $logger->shouldReceive('error')->withAnyArgs();
        $logger->shouldReceive('warning')->withAnyArgs();
        $logger->shouldReceive('notice')->withAnyArgs();
        $logger->shouldReceive('info')->withAnyArgs();
        $logger->shouldReceive('debug')->withAnyArgs();

        return $logger;
    }
}
