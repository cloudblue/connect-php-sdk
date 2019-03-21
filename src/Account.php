<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Account
 * @package Connect
 */
class Account extends Model
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

    public $external_uid;

    /**
     * @var
     */
    public $external_id;

    /**
     * @var ContactInfo
     */

    public $contact_info;
}
