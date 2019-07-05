<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Events
 * @package Connect
 */
class Events extends Model
{
    /**
     * @var string|null
     */
    public $inquired;
    /**
     * @var string|null
     */
    public $updated;
    /**
     * @var string|null
     */
    public $created;

    /**
     * @var string|null
     */
    public $approved;

    /**
     * @var string|null
     */
    public $pended;
}
