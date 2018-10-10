<?php

namespace Connect\Runtime;

use Pimple\Container;

/**
 * Interface BuilderInterface
 * @package Connect\Runtime\Provider
 */
interface ServiceProviderInterface
{
    /**
     * Implements the necessary code to bootstrap and
     * load a given service.
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container);
}