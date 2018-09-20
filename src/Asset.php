<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Asset
 * @package Connect
 */
class Asset
{
    public $id;
    public $external_id;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var Connection
     */
    public $connection;

    /**
     * @var Item[]
     */
    public $items;

    /**
     * @var Param{id}
     */
    public $params;

    /**
     * @var Tier{}
     */
    public $tiers;
}