<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use GuzzleHttp\ClientInterface;
use Pimple\Container;
use Pimple\Psr11\Container as PSRContainer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class FulfillmentAutomation
 * @property Config $config
 * @property LoggerInterface $logger
 * @property ClientInterface $http
 *
 * @package Connect
 */
abstract class FulfillmentAutomation implements FulfillmentAutomationInterface
{
    /**
     * Internal Dependency Container
     * @var ContainerInterface
     */
    private $_container;

    /**
     * FulfillmentAutomation constructor.
     * @param Config|null $configuration
     * @param Container $container
     * @throws ConfigException
     */
    public function __construct(Config $configuration = null, Container $container = null)
    {
        if (!isset($configuration)) {
            $configuration = new Config('./config.json');
        }

        if (!isset($container)) {
            $container = new Container();
        }

        $container['config'] = $configuration;

        foreach ($configuration->runtimeServices as $id => $serviceProvider) {
            if (!isset($container[$id]) && class_exists($serviceProvider, true)) {
                $container[$id] = new $serviceProvider();
            }
        }

        $this->_container = new PSRContainer($container);
    }

    /**
     * Provide an access to the common libraries of the controller
     * @param string $id
     * @return object
     */
    public function __get($id)
    {
        return ($this->_container->has($id))
            ? $this->_container->get($id)
            : null;
    }

    /**
     * Send the actual request to the connect endpoint
     * @param string $verb
     * @param string $path
     * @param null|Model|string $body
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendRequest($verb, $path, $body = null)
    {
        if ($body instanceof \Connect\Model) {
            $body = $body->toJSON(true);
        }

        $headers = [
            'Authorization' => 'ApiKey ' . $this->config->apiKey,
            'Request-ID' => uniqid('api-request-'),
            'Content-Type' => 'application/json',
        ];

        $this->logger->info('HTTP Request: ' . strtoupper($verb) . ' ' . $this->config->apiEndpoint . $path);
        $this->logger->debug("Request Headers:\n" . print_r($headers, true));

        if (isset($body)) {
            $this->logger->debug("Request Body:\n" . $body);
        }

        $response = $this->http->request(strtoupper($verb), trim($this->config->apiEndpoint . $path), [
            'body' => $body,
            'headers' => $headers
        ]);

        $this->logger->info('HTTP Code: ' . $response->getStatusCode());

        return $response->getBody()->getContents();
    }

    /**
     * Process all requests
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function process()
    {
        foreach ($this->listRequests(['status' => 'pending']) as $request) {
            $this->dispatch($request);
        }
    }

    /**
     * @param Request $request
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function dispatch($request)
    {
        try {

            if ($this->config->products && !in_array($request->asset->product->id, $this->config->products)) {
                return 'Invalid product';
            }

            $processingResult = 'unknown';

            $this->logger->info("Starting processing of request ID=" . $request->id);

            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $msg = $this->processRequest($request);
            if (!$msg || is_string($msg)) {
                $msg = new ActivationTileResponse($msg);
            }

            if ($msg instanceof ActivationTemplateResponse) {
                $this->sendRequest('POST', '/requests/' . $request->id . '/approve',
                    '{"template_id": "' . $msg->templateid . '"}');
                $processingResult = 'succeed (Activated using template ' . $msg->templateid . ')';
            } else {
                $this->sendRequest('POST', '/requests/' . $request->id . '/approve',
                    '{"activation_tile": "' . $msg->activationTile . '"}');
                $processingResult = 'succeed (' . $msg->activationTile . ')';
            }

        } catch (Inquire $e) {
            // update parameters and move to inquire
            $this->updateParameters($request, $e->params);
            $this->sendRequest('POST', '/requests/' . $request->id . '/inquire', '{}');
            $processingResult = 'inquire';

        } catch (Fail $e) {
            // fail request
            $this->sendRequest('POST', '/requests/' . $request->id . '/fail',
                '{"reason": "' . $e->getMessage() . '"}');
            $processingResult = 'fail';

        } catch (Skip $e) {
            $processingResult = 'skip';

        }

        $this->logger->info("Finished processing of request ID=" . $request->id . " result=" . $processingResult);

        return $processingResult;
    }

    /**
     * List the pending requests
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return array|Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listRequests(array $filters = null)
    {
        $query = '';
        $filters = $filters ? array_merge($filters) : array();

        if ($this->config->products) {
            $filters['product_id'] = $this->config->products;
        }

        if ($filters) {
            $query = http_build_query($filters);

            // process case when value for filter is array
            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', $query);
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
     * List the pending tier/Config-requests
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return array|Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listTierConfigs(array $filters = null)
    {
        $query = '';
        $filters = $filters ? array_merge($filters) : array();

         if ($filters) {
            $query = http_build_query($filters);

            // process case when value for filter is array
            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', $query);
        }

        $body = $this->sendRequest('GET', '/tier/config-requests' . $query);

        /** @var Request[] $models */
        $models = Model::modelize('tierconfigrequests', json_decode($body));
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
     *          array(
     *              $request->asset->params['param_a']->error('Unknown activation ID was provided'),
     *              $request->asset->params['param_b']->value('true'),
     *              new \Connect\Param(['id' => 'param_c', 'newValue'])
     *          )
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateParameters(Request $request, array $params)
    {
        $body = new \Connect\Request(['asset' => ['params' => $params]]);
        $this->sendRequest('PUT', '/requests/' . $request->id, $body);
    }

    /**
     * Gets Activation template for a given request
     * @param $templateId - ID of template requested
     * @param $request - ID of request or Request object
     * @return string - Rendered template
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function renderTemplate($templateId, $request)
    {
        $query = ($request instanceof Request) ? $request->id : $request;
        return $this->sendRequest('GET', '/templates/' . $templateId . '/render?request_id=' . $query);
    }
}