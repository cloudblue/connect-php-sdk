<?php

namespace Test\Unit\Runtime\Providers;

use Connect\Runtime\ServiceProvider;
use Pimple\Container;

/**
 * Class StdClassServiceProvider
 * @package Test\Unit\Runtime\Providers
 */
class StdClassServiceProvider extends ServiceProvider
{
    public function register(Container $container)
    {
        return new \stdClass();
    }
}
