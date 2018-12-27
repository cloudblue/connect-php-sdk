<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Contact
 * @package Connect
 */
class Contact extends Model
{
    public $email;
    public $first_name;
    public $last_name;

    /**
     * @var PhoneNumber
     */
    public $phone_number;
}