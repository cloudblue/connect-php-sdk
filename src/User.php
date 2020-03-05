<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Account
 * @package Connect
 */
class User extends Model
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
    public $email;
}
