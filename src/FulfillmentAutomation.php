<?php

namespace Connect;

use Pimple\Container;
use Pimple\Psr11\Container as PSRContainer;
use Psr\Container\ContainerInterface;

/**
 * Class FulfillmentAutomation
 * @property Config $config
 * @property Logger $logger
 * @package Connect
 */
abstract class FulfillmentAutomation implements FulfillmentAutomationInterface
{

    private $_container;

    /**
     * FulfillmentAutomation constructor.
     * @param Config|null $configuration
     * @param ContainerInterface|null $container
     * @throws ConfigException
     */
    public function __construct(Config $configuration = null, ContainerInterface $container = null)
    {
        if (!isset($configuration)) {
            $configuration = new Config('./config.json');
        }

        if (!isset($container)) {

            $container = new Container();
            $container['config'] = $configuration;

            /** @var array $builders */
            $runtimeServices = $configuration->get('runtimeServices');

            foreach ($runtimeServices as $id => $serviceProvider) {
                if (class_exists($serviceProvider, true)) {
                    $container[$id] = new $serviceProvider();
                }
            }


            $this->_container = new PSRContainer($container);

        } else {
            $this->_container = $container;
        }
    }

    /**
     * Provide an access to the common libraries of the controller
     * @param string $id
     * @return object
     */
    public function __get($id)
    {
        if ($this->_container->has($id)) {
            return $this->_container->get($id);
        }
        return null;
    }
}