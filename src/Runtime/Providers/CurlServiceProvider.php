<?php

namespace Connect\Runtime\Providers;

use Connect\Runtime\ServiceProvider;
use Pimple\Container;

/**
 * Class CurlProvider
 * @package Connect\RuntimeProviders
 */
class CurlServiceProvider extends ServiceProvider
{
    /**
     * Create the Curl Service
     * @param Container $container
     * @return bool|mixed
     */
    public function register(Container $container)
    {
        return new \stdClass();
    }
}