<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Connection
 * @package Connect
 */
class Connection extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $type;

    /**
     * @var Hub
     */
    public $hub;

    /**
     * @var Provider
     */
    public $provider;

    /**
     * @var Vendor
     */
    public $vendor;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $created_at;
}
