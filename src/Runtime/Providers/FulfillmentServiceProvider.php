<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Runtime\Providers;

use Connect\Config;
use Connect\Modules\Fulfillment;
use Connect\Modules\TierConfiguration;
use Connect\Runtime\ServiceProvider;
use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Log\LoggerInterface;

/**
 * Class FulfillmentServiceProvider
 * @package Connect\Runtime\Providers
 */
class FulfillmentServiceProvider extends ServiceProvider
{
    /**
     * Create the Fulfillment Service
     * @param Container $container
     * @return Fulfillment
     */
    public function register(Container $container)
    {
        /** @var Config $configuration */
        $configuration = $container['config'];

        /** @var LoggerInterface $logger */
        $logger = $container['logger'];

        /** @var Client $http */
        $http = $container['http'];

        /** @var TierConfiguration $tierConfiguration */
        $tierConfiguration = $container['tierConfiguration'];

        return new Fulfillment($configuration, $logger, $http, $tierConfiguration);
    }
}
