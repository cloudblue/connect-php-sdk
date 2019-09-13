<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;


class ProductConfigurationParameter extends Model
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var Param
     */

    public $parameter;

    /**
     * @var Constraints
     */

    public $constraints;

    /**
     * @var Events[]
     */

    public $events;

    /**
     * @var Item
     */

    public $item;

    /**
     * @var Marketplace
     */

    public $marketplace;

}