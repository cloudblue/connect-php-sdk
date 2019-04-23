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
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Fulfilment
 * @package Connect\Modules
 */
class Fulfilment extends Core
{
    /**
     * List the pending requests
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return array|Model
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
     * @param $templateId - ID of template requested
     * @param $request - ID of request or Request object
     * @return string - Rendered template
     * @throws GuzzleException
     */
    public function renderTemplate($templateId, $request)
    {
        $query = ($request instanceof Request) ? $request->id : $request;
        return $this->sendRequest('GET', '/templates/' . $templateId . '/render?request_id=' . $query);
    }
}