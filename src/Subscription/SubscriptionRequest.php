<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Subscription;

use Connect\ConnectClient;
use Connect\Constants;
use Connect\Events;
use Connect\Model;

/**
 * Class SubscriptionRequest
 * @package Connect\Subscription
 */
class SubscriptionRequest extends Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var Events
     */
    public $events;

    /**
     * @var SubscriptionAsset
     */
    public $asset;

    /**
     * @var Item[]
     */
    public $items;

    /**
     * @var Attributes
     */
    public $attributes;

    /**
     * @var SubscriptionPeriod
     */
    public $period;

    public function setVendorAttributes(Model $model)
    {
        $output = ConnectClient::getInstance()->subscriptions->sendRequest('PUT', Constants::SUBSCRIPTIONS_REQUESTS_PATH.$this->id.'/attributes', json_encode(array("vendor" => $model->toArray())));
        $this->attributes->vendor =  Model::modelize('Model', json_decode($output));
    }

    public function setProviderAttributes(Model $model)
    {
        $output = ConnectClient::getInstance()->subscriptions->sendRequest('PUT', Constants::SUBSCRIPTIONS_REQUESTS_PATH.$this->id.'/attributes', json_encode(array("provider" => $model->toArray())));
        $this->attributes->provider =  Model::modelize('Model', json_decode($output));
    }

    public function setPeriod($period)
    {
        $this->period = Model::modelize('SubscriptionPeriod', $period);
    }
}
