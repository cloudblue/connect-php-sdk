<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Tier
 * @package Connect
 */
class Tier extends Model
{
    public $id;
    public $external_id;
    public $name;

    /**
     * @var ContactInfo
     */
    public $contact_info;
}