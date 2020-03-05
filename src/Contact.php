<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Contact
 * @package Connect
 */
class Contact extends Model
{
    /**
     * @var string | null
     */
    public $email;
    /**
     * @var string | null
     */
    public $first_name;
    /**
     * @var string | null
     */
    public $last_name;

    /**
     * @var PhoneNumber
     */
    public $phone_number;
}
