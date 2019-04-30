<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Connect\Modules\Fulfillment;
use Connect\Modules\TierConfiguration;
use Connect\Modules\Usage;
use GuzzleHttp\ClientInterface;
use Pimple\Container;
use Pimple\Psr11\Container as PSRContainer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AutomationEngine
 * @property Config $config
 * @property LoggerInterface $logger
 * @property ClientInterface $http
 * @property Fulfillment $fulfillment
 * @property TierConfiguration $tierConfiguration
 * @property Usage $usage
 * @package Connect
 */
abstract class AutomationEngine
{
    /**
     * Internal Dependency Container
     * @var ContainerInterface
     */
    private $_container;

    /**
     * List of services with shortcuts for old implementations
     * @internal
     * @var array
     */
    private $_shorcuts = [
        'fulfillment',
        'tierConfiguration',
        'usage',
    ];

    /**
     * FulfillmentAutomation constructor.
     * @param Config|null $configuration
     * @param Container $container
     * @throws ConfigException
     */
    public function __construct(Config $configuration = null, Container $container = null)
    {
        if (!isset($configuration)) {
            $configuration = new Config('./config.json');
        }

        if (!isset($container)) {
            $container = new Container();
        }

        $container['config'] = $configuration;

        foreach ($configuration->runtimeServices as $id => $serviceProvider) {
            if (!isset($container[$id]) && class_exists($serviceProvider, true)) {
                $container[$id] = new $serviceProvider();
            }
        }

        $this->_container = new PSRContainer($container);
    }

    /**
     * Provide an access to the common libraries of the controller
     * @param string $id
     * @return object
     */
    public function __get($id)
    {
        return ($this->_container->has($id))
            ? $this->_container->get($id)
            : null;
    }

    /**
     * Dynamically call connect native module functions.
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        foreach ($this->_shorcuts as $id) {
            if (is_callable([$this->_container->get($id), $name])) {
                return call_user_func_array([$this->_container->get($id), $name], $arguments);
            }
        }
    }
}
