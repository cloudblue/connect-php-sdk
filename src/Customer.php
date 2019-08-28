<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Customer
 * @package Connect
 */
class Customer extends Model
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
    public $external_id;

    /**
     * @var ContactInfo
     */
    public $contact_info;

    /**
     * @var string
     */
    public $external_uid;
}
