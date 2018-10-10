<?php

namespace Connect\Runtime;

use Pimple\Container;

/**
 * Class Builder
 * @package Connect
 */
abstract class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Provide the service
     * @param $container
     * @return mixed
     */
    public function __invoke(Container $container)
    {
        return $this->register($container);
    }
}