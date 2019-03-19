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
    public $id;
    public $name;
    public $external_id;

    /**
     * @var ContactInfo
     */
    public $contact_info;
}
