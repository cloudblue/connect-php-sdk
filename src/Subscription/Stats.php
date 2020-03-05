<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Subscription;

use Connect\Model;

/**
 * Class Stats
 * @package Connect\Subscription
 */
class Stats extends Model
{
    /**
     * @var ActorStats
     */
    public $vendor;

    /**
     * @var ActorStats
     */
    public $provider;

    public function setVendor($vendor)
    {
        $this->vendor = Model::modelize('ActorStats', $vendor);
    }

    public function setProvider($provider)
    {
        $this->provider = Model::modelize('ActorStats', $provider);
    }
}
