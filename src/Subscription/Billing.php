<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Subscription;

use Connect\Model;

/**
 * Class Billing
 * @package Connect\Subscription
 */
class Billing extends Model
{
    /**
     * @var Stats
     */
    public $stats;

    /**
     * @var SubscriptionPeriod
     */
    public $period;

    /**
     * @var string
     */
    public $next_date;

    /**
     * @var Anniversary
     */
    public $anniversary;

    public function setStats($stats)
    {
        $this->stats = Model::modelize('Subscription\Stats', $stats);
    }

    public function setPeriod($period)
    {
        $this->period = Model::modelize('Subscription\Period', $period);
    }
}
