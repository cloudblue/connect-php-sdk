<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Modules;

use Connect\Config;
use Connect\ConnectClient;
use Connect\Model;
use Connect\Param;
use Connect\Request;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * Class Fulfillment
 * @package Connect\Modules
 */
class Fulfillment extends Core
{
    /**
     * TierConfiguration Service
     * @var TierConfiguration
     */
    public $tierConfiguration;

    /**
     * Fulfillment constructor.
     * @param Config $config
     * @param LoggerInterface $logger
     * @param ClientInterface $http
     * @param TierConfiguration $tierConfiguration
     */
    public function __construct(Config $config, LoggerInterface $logger, ClientInterface $http, TierConfiguration $tierConfiguration)
    {
        parent::__construct($config, $logger, $http);

        $this->tierConfiguration = $tierConfiguration;
    }

    /**
     * List the pending requests
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return array|Request
     * @throws GuzzleException
     */
    public function listRequests(array $filters = [])
    {
        $query = '';

        if ($this->config->products) {
            $filters['asset.product.id__in'] = implode(",", $this->config->products);
        }

        if ($filters) {
            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', http_build_query($filters));
            $query = urldecode($query);
        }

        $body = $this->sendRequest('GET', '/requests' . $query);

        /** @var Request[] $models */
        $models = Model::modelize('requests', json_decode($body));
        foreach ($models as $index => $model) {
            $models[$index]->requestProcessor = $this;
        }

        return $models;
    }

    /**
     * Update request parameters
     * @param Request $request - request being updated
     * @param Param[] $params - array of parameters
     *      Example:
     *          [
     *              $request->asset->params['param_a']->error('Unknown activation ID was provided'),
     *              $request->asset->params['param_b']->value('true'),
     *              new \Connect\Param(['id' => 'param_c', 'newValue'])
     *          ]
     * @throws GuzzleException
     */
    public function updateParameters(Request $request, array $params)
    {
        $body = new Request(['asset' => ['params' => $params]]);
        $this->sendRequest('PUT', '/requests/' . $request->id, $body);
    }

    /**
     * Gets Activation template for a given request
     * @param string $templateId - ID of template requested
     * @param string $request - ID of request or Request object
     * @return string - Rendered template
     * @throws GuzzleException
     */
    public function renderTemplate($templateId, $request)
    {
        $query = ($request instanceof Request) ? $request->id : $request;
        return $this->sendRequest('GET', '/templates/' . $templateId . '/render?request_id=' . $query);
    }

    /**
     * Dynamically call connect native module functions.
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (is_callable([$this->tierConfiguration, $name])) {
            return call_user_func_array([$this->tierConfiguration, $name], $arguments);
        }

        return null;
    }

    /**
     * @param $requestId
     * @return array|Request
     * @throws GuzzleException
     * @throws \Connect\ConfigException
     */
    public function getRequest($requestId)
    {
        $body = ConnectClient::getInstance()->fulfillment->sendRequest('GET', '/requests/'.$requestId);
        return Model::modelize('Request', json_decode($body));
    }

    /**
     * To be used only with provider token
     * @param Request $request
     * @return array|Request
     * @throws GuzzleException
     * @throws \Connect\ConfigException
     */
    public function createRequest(Request $request)
    {
        $body = ConnectClient::getInstance()->fulfillment->sendRequest('POST', '/requests', $request);
        return Model::modelize('Request', json_decode($body));
    }
}
