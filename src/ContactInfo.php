<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ContactInfo
 * @package Connect
 */
class ContactInfo extends Model
{
    public $address_line1;
    public $address_line2;
    public $city;

    /**
     * @var Contact
     */
    public $contact;
    public $country;
    public $postal_code;
    public $state;
}

