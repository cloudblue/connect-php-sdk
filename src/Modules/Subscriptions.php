<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Modules;

use Connect\Config;
use Connect\Constants;
use Connect\Model;
use Connect\Subscription\SubscriptionRequest;
use Connect\Subscription\SubscriptionAsset;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Connect\RQL;

/**
 * Class Subscriptions
 * @package Connect\Modules
 */
class Subscriptions extends Core
{
    /**
     * Directory constructor.
     * @param Config $config
     * @param LoggerInterface $logger
     * @param ClientInterface $http
     */
    public function __construct(Config $config, LoggerInterface $logger, ClientInterface $http)
    {
        parent::__construct($config, $logger, $http);
    }

    /**
     * @param null $filters
     * @return array|SubscriptionAsset
     * @throws GuzzleException
     * Possible filters:
     * events.created.at (le, ge)
     * events.created.at (le, ge)
     * billing.period.uom
     * billing.next_date
     * external_id
     * external_uid
     * product.id
     * product.name
     * connection.id
     * connection.type
     * connection.provider.id
     * connection.provider.name
     * connection.vendor.id
     * connection.vendor.name
     * connection.hub.id
     * tiers.customer.id (Customer ID)
     * tiers.tier1.id
     * tiers.tier2.id
     */

    public function listSubscriptionAssets($filters = null)
    {
        $query = new \Connect\RQL\Query();

        if (is_array($filters)) {
            $query = new \Connect\RQL\Query($filters);
        } elseif ($filters instanceof \Connect\RQL\Query) {
            $query = $filters;
        }

        if ($this->config->products) {
            $query->in('product.id', is_array($this->config->products)
                ? $this->config->products
                : explode(',', $this->config->products));
        }

        $body = $this->sendRequest('GET', Constants::SUBSCRIPTIONS_ASSETS_PATH . $query->compile());

        /** @var \Connect\Subscription\SubscriptionAsset[] $models */
        return Model::modelize('SubscriptionAsset', json_decode($body));
    }

    /**
     * @param $id
     * @return SubscriptionAsset
     * @throws GuzzleException
     */
    public function getSubscriptionAssetById($id)
    {
        $body = $this->sendRequest('GET', Constants::SUBSCRIPTIONS_ASSETS_PATH .$id);
        return Model::modelize('SubscriptionAsset', json_decode($body));
    }

    /**
     * @param null $filters
     * @return array|SubscriptionRequest
     * @throws GuzzleException
     * Possible filters:
     * type
     * events.created.at (le, ge)
     * events.created.at (le, ge)
     * asset.billing.period.uom
     * asset.billing.next_date
     * asset.external_id
     * asset.external_uid
     * asset.product.id
     * asset.product.name
     * asset.connection.id
     * asset.connection.type
     * asset.connection.provider.id
     * asset.connection.provider.name
     * asset.connection.vendor.id
     * asset.connection.vendor.name
     * asset.connection.hub.id
     * asset.tiers.customer.id (Customer ID)
     * asset.tiers.tier1.id
     * asset.tiers.tier2.id
     */
    public function listSubscriptionRequests($filters = null)
    {
        $query = new \Connect\RQL\Query();

        if (is_array($filters)) {
            $query = new \Connect\RQL\Query($filters);
        } elseif ($filters instanceof \Connect\RQL\Query) {
            $query = $filters;
        }

        if ($this->config->products) {
            $query->in('product.id', is_array($this->config->products)
                ? $this->config->products
                : explode(',', $this->config->products));
        }

        $body = $this->sendRequest('GET', Constants::SUBSCRIPTIONS_REQUESTS_PATH . $query->compile());
        /** @var \Connect\Subscription\SubscriptionRequest[] $models */
        return Model::modelize('SubscriptionRequests', json_decode($body));
    }

    public function getSubscriptionRequestById($id)
    {
        $body = $this->sendRequest('GET', Constants::SUBSCRIPTIONS_REQUESTS_PATH .$id);
        return Model::modelize('SubscriptionRequest', json_decode($body));
    }
}
