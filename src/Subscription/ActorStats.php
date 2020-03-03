<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Subscription;

use Connect\Model;

/**
 * Class ActorStats
 * @package Connect\Subscription
 */
class ActorStats extends Model
{
    /**
     * @var int
     */

    public $count;

    /**
     * @var SubscriptionRequests
     */
    public $last_request;
}
