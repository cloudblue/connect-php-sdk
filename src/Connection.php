<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Connection
 * @package Connect
 */
class Connection extends Model
{
    public $id;
    public $name;

    /**
     * @var Provider
     */
    public $provider;

    /**
     * @var Vendor
     */
    public $vendor;
}