<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Subscription;

use Connect\Model;

class SubscriptionPeriod extends Model
{
    /**
     * @var string
     */
    public $delta;

    /**
     * @var string
     */
    public $uom;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $to;
}
