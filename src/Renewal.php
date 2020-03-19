<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Renewal
 * @package Connect
 */
class Renewal extends Model
{
    /**
     * @var string
     */
    public $from;
    /**
     * @var string
     */
    public $to;
    /**
     * @var int
     */
    public $period_delta;
    /**
     * @var string
     */
    public $period_uom;
}
