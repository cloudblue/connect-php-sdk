<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class RequestsProcessor
 * @package Connect
 */
abstract class RequestsProcessor extends FulfillmentAutomation
{
    /**
     * RequestsProcessor constructor.
     * @param null $config
     * @throws ConfigException
     */
    public function __construct($config = null)
    {
        if (!isset($config)) {
            $config = './config.json';
        }
        parent::__construct(($config instanceof Config) ? $config : new Config($config));
    }
}
