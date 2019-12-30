<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Modules;

use Connect\Config;
use Connect\Model;
use Connect\Asset;
use Connect\Product;
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
     * @param  $filters, it may be due backwards compatibility an array of key->value
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
        $body = $this->sendRequest('GET', '/products/'.$productID);
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
        $body = $this->sendRequest('GET', '/tier/configs/' . $id);
        /** @var \Connect\TierConfig $model */
        return Model::modelize('tierConfig', json_decode($body));
    }
}
