<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Configuration
 * @package Connect
 */
class Configuration extends Model
{
    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $status;

    /**
     * @var Account
     */

    public $account;

    /**
     * @var Product
     */

    public $product;

    /**
     * @var
     */
    public $tier_level;

    /**
     * @var Events
     */

    public $events;

    /**
     * @var Param[]
     */
    public $params;
}