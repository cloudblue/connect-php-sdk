<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

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