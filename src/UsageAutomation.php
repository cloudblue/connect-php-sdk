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
 * Class UsageAutomation
 * @property Config $config
 * @property LoggerInterface $logger
 * @property ClientInterface $http
 *
 * @package Connect
 */
abstract class UsageAutomation implements UsageAutomationInterface
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
        foreach ($this->listListings(['status' => 'listed']) as $listing) {
            $this->dispatchProductUsageCollection($listing);
        }
    }

    /**
     * @param Listing $listing
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function dispatchProductUsageCollection($listing)
    {
        $processingResult = 'unknown';

    }

    /**
     * List the pending requests
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return array|Model
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listListings(array $filters = null)
    {
        $query = '';

        if ($this->config->products) {
            $filters['product__id'] = $this->config->products;
        }

        if ($filters) {
            $query = http_build_query($filters);

            // process case when value for filter is array
            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', $query);
        }

        $body = $this->sendRequest('GET', '/listings' . $query);

        /** @var Request[] $models */
        $models = Model::modelize('listings', json_decode($body));
        foreach ($models as $index => $model) {
            $models[$index]->requestProcessor = $this;
        }

        return $models;
    }
}