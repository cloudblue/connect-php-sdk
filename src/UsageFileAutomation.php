<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Connect\Usage\Accept;
use Connect\Usage\Close;
use Connect\Usage\Delete;
use Connect\Usage\File;
use Connect\Usage\Reject;
use Connect\Usage\Submit;
use GuzzleHttp\ClientInterface;
use Pimple\Container;
use Pimple\Psr11\Container as PSRContainer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class UsageFileAutomation
 * @property Config $config
 * @property LoggerInterface $logger
 * @property ClientInterface $http
 *
 * @package Connect
 */
abstract class UsageFileAutomation implements UsageFileAutomationInterface
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
        if ($body instanceof Model) {
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
     * @param bool $all - optional, if true gets all usage files
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function process($all=false)
    {
        $filter = [];
        if($all==false){
            $filter = ['status' => 'ready'];
        }
        foreach ($this->listUsageFiles($filter) as $usageFile) {
            $this->dispatchUsageFileProcessing($usageFile);
        }
    }

    /**
     * @param Usage\File $usageFile
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function dispatchUsageFileProcessing($usageFile)
    {
        try {
            if ($this->config->products && !in_array($usageFile->product->id, $this->config->products)) {
                return 'Invalid product';
            }

            $processingResult = 'unknown';

            $this->logger->info("Starting processing of Usage File ID=" . $usageFile->id);

            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $msg = $this->processUsageFiles($usageFile);

            if (is_string($msg)) {
                $this->logger->warning("ProcessUsageFiles returned $msg while is expected to return Exception of Usage types");
            } elseif ($msg) {
                $this->logger->warning("ProcessUsageFiles returned instance of " . gettype($msg) . " while is expected to return Exception of Usage types");
            }

        } catch (Accept $e) {
            $this->sendRequest('POST', '/usage/files/' . $usageFile->id . '/accept/',
                '{"acceptance_note": "' . $e->getMessage() . '""}');
            $processingResult = 'accept';

        } catch (Close $e) {
            $this->sendRequest('POST', '/usage/files/' . $usageFile->id . '/close/',
                '{}');
            $processingResult = 'close';
        } catch (Delete $e) {
            $this->sendRequest('POST', '/usage/files/' . $usageFile->id . '/delete/',
                '{}');
            $processingResult = 'delete';
        } catch (Reject $e) {
            $this->sendRequest('POST', '/usage/files/' . $usageFile->id . '/reject/',
                '{"rejection_note": "' . $e->getMessage() . '""}');
            $processingResult = 'reject';

        } catch (Submit $e) {
            $this->sendRequest('POST', '/usage/files/' . $usageFile->id . '/submit/',
                '{}');
            $processingResult = 'submit';
        } catch (\Connect\Usage\Skip $e) {
            $processingResult = 'skip';
        }

        $this->logger->info("Finished processing of Usage File ID=" . $usageFile->id . " result=" . $processingResult);

        return $processingResult;
    }

    /**
     * List the pending requests
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return Usage\File[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listUsageFiles(array $filters = null)
    {
        $query = '';

        if ($this->config->products) {
            $filters['product_id'] = $this->config->products;
        }

        if ($filters) {
            $query = http_build_query($filters);

            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', $query);
        }

        $body = $this->sendRequest('GET', '/usage/files/' . $query);

        /** @var Request[] $models */
        $models = Model::modelize('files', json_decode($body));
        foreach ($models as $index => $model) {
            $models[$index]->requestProcessor = $this;
        }

        return $models;
    }
}