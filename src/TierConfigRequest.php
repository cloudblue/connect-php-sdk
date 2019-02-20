<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class TierConfigRequest
 *      APS Connect Tier Config Request object
 * @package Connect
 */
class TierConfigRequest extends Model
{
    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $status;
    /**
     * @var
     */
    public $tier_level;

    /**
     * @var Configuration
     */

    public $configuration;

    /**
     * @var Account
     */

    public $account;

    /**
     * @var Product
     */

    public $product;

    /**
     * @var Events
     */

    public $events;

    /**
     * @var Param[]
     */
    public $params;
}