<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Contract
 * @package Connect
 */
class Contract extends Model
{
    public $id;
    public $name;
    public $type;
    public $status;

    /**
     * @var Agreement
     */
    public $agreement;

    /**
     * @var Owner
     */
    public $owner;

    public $created;
    public $updated;
    public $enrolled;

    public $version;

    /**
     * @var Signee
     */
    public $signee;

    public $version_created;

    /**
     * @var Marketplace
     */
    public $marketplace;
}
