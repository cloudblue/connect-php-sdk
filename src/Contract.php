<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Contract
 * @package Connect
 */
class Contract extends Model
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
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $status;

    /**
     * @var Agreement
     */
    public $agreement;

    /**
     * @var Owner
     */
    public $owner;

    /**
     * @var string | null
     */
    public $created;
    /**
     * @var string | null
     */
    public $updated;
    /**
     * @var string | null
     */
    public $enrolled;

    /**
     * @var int
     */
    public $version;

    /**
     * @var Signee
     */
    public $signee;

    /**
     * @var string
     */
    public $version_created;

    /**
     * @var Marketplace
     */
    public $marketplace;
}
