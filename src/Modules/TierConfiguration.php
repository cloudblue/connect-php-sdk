<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Modules;

use Connect\Model;
use Connect\Param;
use Connect\Request;
use Connect\TierConfigRequest;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class TierConfiguration
 * @package Connect\Modules
 */
class TierConfiguration extends Core
{
    /**
     * List the pending tier/Config-requests
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return array|TierConfigRequest
     * @throws GuzzleException
     */
    public function listTierConfigs(array $filters = null)
    {
        $query = '';

        if ($filters) {
            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', http_build_query($filters));
        }

        $body = $this->sendRequest('GET', '/tier/config-requests' . $query);

        /** @var Request[] $models */
        $models = Model::modelize('tierConfigRequests', json_decode($body));
        foreach ($models as $index => $model) {
            $models[$index]->requestProcessor = $this;
        }

        return $models;
    }

    /**
     * Update tierConfig parameters
     * @param TierConfigRequest $tierConfigRequest - TierConfigRequest being updated
     * @param Param[] $params - array of parameters
     * @throws GuzzleException
     */
    public function updateTierConfigRequestParameters(TierConfigRequest $tierConfigRequest, array $params)
    {
        $body = new TierConfigRequest(['params' => $params]);
        $this->sendRequest('PUT', '/tier/config-requests/' . $tierConfigRequest->id, $body);
    }

    /**
     * @param string $tierId - Connect ID of the tier
     * @param string $productId - Product ID
     * @return TierConfigRequest|Model
     * @throws GuzzleException
     */
    public function getTierConfigByProduct($tierId, $productId)
    {
        $body = $this->sendRequest(
            'GET',
            '/tier/config-requests?status=approved&configuration__product__id=' . $productId . '&configuration__account__id=' . $tierId
        );

        $model = Model::modelize('tierConfigRequests', json_decode($body));
        if (count($model) > 0) {
            return $model[0]->configuration;
        }

        return $model;
    }

    /**
     * @param string $parameterId
     * @param string $tierId
     * @param string $productId
     * @return Param|null
     * @throws GuzzleException
     */
    public function getTierParameterByProductAndTierId($parameterId, $tierId, $productId)
    {
        $tierConfig = $this->getTierConfigByProduct($tierId, $productId);
        if (!$tierConfig) {
            return null;
        }
        $param = current(array_filter($tierConfig->params, function (Param $param) use ($parameterId) {
            return ($param->id === $parameterId);
        }));

        return ($param) ? $param : null;
    }
}
