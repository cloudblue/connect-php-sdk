<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class PhoneNumber
 * @package Connect
 */
class PhoneNumber extends Model
{
    /**
     * @var string
     */
    public $country_code;
    /**
     * @var string
     */
    public $area_code;
    /**
     * @var string
     */
    public $phone_number;
    /**
     * @var string
     */
    public $extension;
}
