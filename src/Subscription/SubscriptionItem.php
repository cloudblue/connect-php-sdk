<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Subscription;

use Connect\Model;

/**
 * Class SubscriptionItem
 * @package Connect
 */
class SubscriptionItem extends Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $global_id;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     * @deprecated
     */
    public $display_name;

    /**
     * @var string
     */
    public $period;

    /**
     * #var string
     */
    public $item_type;

    /**
     * @var string
     */
    public $mpn;


    /**
     * @var string
     */
    public $name;

    /**
     * @var Billing | null
     */

    public $billing;

    /**
     * @var Param[]
     */
    public $params;

    /**
     * Return a Param by ID
     * @param string $id
     * @return Param
     */
    public function setBilling($billing)
    {
        $this->billing = Model::modelize('Billing', $billing);
    }
}
