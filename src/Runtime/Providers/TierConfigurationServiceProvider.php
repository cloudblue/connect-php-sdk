<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Runtime\Providers;

use Connect\Config;
use Connect\Modules\TierConfiguration;
use Connect\Runtime\ServiceProvider;
use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Log\LoggerInterface;

/**
 * Class TierConfigurationServiceProvider
 * @package Connect\Runtime\Providers
 */
class TierConfigurationServiceProvider extends ServiceProvider
{
    /**
     * Create the TierConfiguration Service
     * @param Container $container
     * @return TierConfiguration
     */
    public function register(Container $container)
    {
        /** @var Config $configuration */
        $configuration = $container['config'];

        /** @var LoggerInterface $logger */
        $logger = $container['logger'];

        /** @var Client $http */
        $http = $container['http'];

        return new TierConfiguration($configuration, $logger, $http, $container);
    }
}
