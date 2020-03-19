<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ContactInfo
 * @package Connect
 */
class ContactInfo extends Model
{
    /**
     * @var string | null
     */
    public $address_line1;
    /**
     * @var string | null
     */
    public $address_line2;
    /**
     * @var string | null
     */
    public $city;

    /**
     * @var Contact
     */
    public $contact;
    /**
     * @var string | null
     */
    public $country;
    /**
     * @var string | null
     */
    public $postal_code;
    /**
     * @var string | null
     */
    public $state;
}
