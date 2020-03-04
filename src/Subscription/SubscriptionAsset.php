<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Subscription;

use Connect\Configuration;
use Connect\Connection;
use Connect\Contract;
use Connect\Events;
use Connect\Marketplace;
use Connect\Param;
use Connect\Product;
use Connect\Tiers;
use Connect\Model;

class SubscriptionAsset extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $status;
    /**
     * @var Events[]
     */
    public $events;
    /**
     * @var string | null
     */
    public $external_id;
    /**
     * @var string | null
     */
    public $external_uid;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var Connection
     */
    public $connection;

    /**
     * @var SubscriptionItem[]
     */
    public $items;

    /**
     * @var Param[]
     */
    public $params;

    /**
     * @var Tiers
     */
    public $tiers;


    /**
     * @var Contract
     */
    public $contract;

    /**
     * @var Marketplace
     */
    public $marketplace;

    /**
     * @var Configuration
     */
    public $configuration;

    /**
     * @var Billing
     */
    public $billing;

    public function setItems($items)
    {
        $this->items = Model::modelize('subscriptionItems', $items);
    }

    public function setBilling($billing)
    {
        $this->billing = Model::modelize('billing', $billing);
    }
}
