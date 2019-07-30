<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class TierConfig
 * @package Connect
 */
class TierConfig extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;

    /**
     * @var Account
     */

    public $account;

    /**
     * @var Product
     */

    public $product;

    /**
     * @var int
     */
    public $tier_level;

    /**
     * @var Connection
     */
    public $connection;

    /**
     * @var Events
     */

    public $events;

    /**
     * @var Param[]
     */
    public $params;

    /**
     * @var OpenRequest
     */
    public $open_request;

    /**
     * @var Template
     */
    public $template;

    /**
     * @var string
     */

    public $status;

    /**
     * @var Contract
     */
    public $contract;

    /**
     * @var Marketplace
     */
    public $marketplace;

    /**
     * @var Configuration
     */
    public $configuration;

    /**
     * Return a Param by ID
     * @param string $id
     * @return Param
     */
    public function getParameterByID($id)
    {
        $param = current(array_filter($this->params, function (Param $param) use ($id) {
            return ($param->id === $id);
        }));

        return ($param) ? $param : null;
    }
}
