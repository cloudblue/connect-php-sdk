<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

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
