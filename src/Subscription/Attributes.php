<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Subscription;

use Connect\Model;

/**
 * Class Attributes
 * @package Connect\Subscription
 */
class Attributes extends Model
{
    /**
     * @var Model
     */
    public $vendor;

    /**
     * @var Model
     */
    public $provider;

    public function setVendor($vendor)
    {
        $this->vendor = Model::modelize('Model', $vendor);
    }

    public function setProvider($provider)
    {
        $this->provider = Model::modelize('Model', $provider);
    }
}
