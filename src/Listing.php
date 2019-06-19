<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class MarketPlace
 * @package Connect
 */
class Listing extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $status;

    /**
     * @var Contract
     */
    public $contract;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var string|null
     */
    public $created;

    /**
     * @var Vendor
     */
    public $vendor;

    /**
     * @var Provider;
     */
    public $provider;
}
