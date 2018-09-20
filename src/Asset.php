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
    var $id;
    var $external_id;

    /**
     * @var Product
     */
    var $product;

    /**
     * @var Connection
     */
    var $connection;

    /**
     * @var Item[]
     */
    var $items;

    /**
     * @var Param{id}
     */
    var $params;

    /**
     * @var Tier{}
     */
    var $tiers;
}