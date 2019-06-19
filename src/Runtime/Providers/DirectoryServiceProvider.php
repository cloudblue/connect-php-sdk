<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Runtime\Providers;

use Connect\Config;
use Connect\Modules\Directory;
use Connect\Runtime\ServiceProvider;
use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Log\LoggerInterface;

/**
 * Class DirectoryServiceProvider
 * @package Connect\Runtime\Providers
 */
class DirectoryServiceProvider extends ServiceProvider
{
    /**
     * Create the DirectoryServiceProvider Service
     * @param Container $container
     * @return Directory
     */
    public function register(Container $container)
    {
        /** @var Config $configuration */
        $configuration = $container['config'];

        /** @var LoggerInterface $logger */
        $logger = $container['logger'];

        /** @var Client $http */
        $http = $container['http'];

        return new Directory($configuration, $logger, $http, $container);
    }
}
