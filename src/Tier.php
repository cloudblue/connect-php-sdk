<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Tier
 * @package Connect
 */
class Tier extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $external_id;
    /**
     * @var string
     */
    public $name;

    /**
     * @var ContactInfo
     */
    public $contact_info;

    /**
     * @var string
     */
    public $external_uid;
}
