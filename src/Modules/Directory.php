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
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

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
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return Asset[]
     * @throws GuzzleException
     */
    public function listAssets(array $filters = [])
    {
        $query = '';

        if ($this->config->products) {
            $filters['asset.product.id__in'] = implode(",", $this->config->products);
        }

        if ($filters) {
            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', http_build_query($filters));
            $query = urldecode($query);
        }

        $body = $this->sendRequest('GET', '/assets' . $query);

        /** @var Asset[] $models */
        $models = Model::modelize('assets', json_decode($body));

        return $models;
    }

    /**
     * @param string $assetID
     * @return Asset
     * @throws GuzzleException
     */
    public function getAssetById($assetID)
    {
        $body = $this->sendRequest('GET', '/assets/' . $assetID);
        $model = Model::modelize('asset', json_decode($body));
        return $model;
    }

    public function listProducts(array $filters = [])
    {
        //Filtering is not possible at this moment on time, requested as feature LITE-9071
        
        $body = $this->sendRequest('GET', '/products');

        /** @var Asset[] $models */
        $models = Model::modelize('products', json_decode($body));

        return $models;
    }
}
