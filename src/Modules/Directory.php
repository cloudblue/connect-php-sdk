<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Modules;

use Connect\Config;
use Connect\Constants;
use Connect\Model;
use Connect\Asset;
use Connect\Product;
use Connect\TierAccount;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Connect\RQL;

/**
 * Class Directory
 * @package Connect
 */
class Directory extends Core
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
     * List the Assets
     * @param  $filters , it may be due backwards compatibility an array of key->value
     *          or object of class \Connect\RQL
     * @return Asset[]
     * @throws GuzzleException
     */
    public function listAssets($filters = null)
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

        $body = $this->sendRequest('GET', '/assets' . $query->compile());

        /** @var Asset[] $models */
        return Model::modelize('assets', json_decode($body));
    }

    /**
     * @param string $assetID
     * @return Asset
     * @throws GuzzleException
     */
    public function getAssetById($assetID)
    {
        $body = $this->sendRequest('GET', '/assets/' . $assetID);
        /** @var Asset $model */
        return Model::modelize('asset', json_decode($body));
    }

    /**
     * @return Product[]
     * @throws GuzzleException
     */
    public function listProducts($filters = null)
    {
        if ($filters instanceof \Connect\RQL\Query) {
            $query = $filters;
        } elseif (is_array($filters)) {
            $query = new \Connect\RQL\Query($filters);
        } else {
            $query = new \Connect\RQL\Query();
        }

        $body = $this->sendRequest('GET', '/products' . $query->compile());

        /** @var \Connect\Product[] $models */
        return Model::modelize('products', json_decode($body));
    }

    /**
     * @param string $productID
     * @return \Connect\Product
     * @throws GuzzleException
     */

    public function getProduct($productID)
    {
        $body = $this->sendRequest('GET', '/products/' . $productID);
        /** @var Product $model */
        return Model::modelize('product', json_decode($body));
    }

    /**
     * @param array $filters
     * @return \Connect\TierConfig[]
     * @throws GuzzleException
     */
    public function listTierConfigs($filters = null)
    {
        if ($filters instanceof \Connect\RQL\Query) {
            $query = $filters;
        } elseif (is_array($filters)) {
            $query = new \Connect\RQL\Query($filters);
        } else {
            $query = new \Connect\RQL\Query();
        }

        if ($this->config->products) {
            $query->in('product.id', is_array($this->config->products)
                ? $this->config->products
                : explode(',', $this->config->products));
        }

        $body = $this->sendRequest('GET', '/tier/configs' . $query->compile());

        /** @var \Connect\TierConfig[] $models */
        return Model::modelize('tierConfig', json_decode($body));
    }

    /**
     * @param $id
     * @return \Connect\TierConfig
     * @throws GuzzleException
     */
    public function getTierConfigById($id)
    {
        $body = $this->sendRequest('GET', \CONNECT\Constants::TIER_CONFIG_PATH . $id);
        /** @var \Connect\TierConfig $model */
        return Model::modelize('tierConfig', json_decode($body));
    }

    /**
     * @param null $filters
     * @return array|TierAccount
     * @throws GuzzleException
     * Available filters
     * id
     * external_id
     * external_uid
     * environment
     * scopes
     * marketplace.id
     * marketplace.name
     * hub.id
     * hub.name
     */
    public function listTierAccounts($filters = null)
    {
        if ($filters instanceof \Connect\RQL\Query) {
            $query = $filters;
        } elseif (is_array($filters)) {
            $query = new \Connect\RQL\Query($filters);
        } else {
            $query = new \Connect\RQL\Query();
        }

        $body = $this->sendRequest('GET', \Connect\Constants::TIER_ACCOUNTS_PATH . $query->compile());
        /** @var \Connect\TierAccount[] $model */
        return Model::modelize('tierAccount', json_decode($body));
    }

    /**
     * @param $id
     * @return array|Model
     * @throws GuzzleException
     */
    public function getTierAccountById($id)
    {
        $body = $this->sendRequest('GET', \Connect\Constants::TIER_ACCOUNTS_PATH  . $id);
        return Model::modelize('tierAccount', json_decode($body));
    }

    /**
     * @param null $filters
     * @return array|Model
     * @throws GuzzleException
     * Possible filters:
     *  -status
     *  -id
     *  -product.id
     *  -product.name
     *  -vendor.id
     *  -vendor.name
     *  -provider.id
     *  -provider.name
     *  -account.id
     *  -account.name
     *  -account.external_id
     *  -account.external_uid
     *  -account.environment
     *  -account.scopes
     *  -account.marketplace.id
     *  -account.marketplace.name
     *  -account.hub.id
     *  -account.hub.name
     */
    public function listTierAccountRequests($filters = null)
    {
        if ($filters instanceof \Connect\RQL\Query) {
            $query = $filters;
        } elseif (is_array($filters)) {
            $query = new \Connect\RQL\Query($filters);
        } else {
            $query = new \Connect\RQL\Query();
        }
        $body = $this->sendRequest('GET', Constants::TIER_ACCOUNT_REQUESTS_PATH. $query->compile());
        /** @var \Connect\TierAccountRequest[] $model */
        return Model::modelize('tierAccountRequest', json_decode($body));
    }

    public function createTierAccountRequest(\Connect\TierAccountRequest $request)
    {
        $body = $this->sendRequest('POST', Constants::TIER_ACCOUNT_REQUESTS_PATH, json_encode($request));
        /** @var \Connect\TierAccountRequest[] $model */
        return Model::modelize('tierAccountRequest', json_decode($body));
    }
}
