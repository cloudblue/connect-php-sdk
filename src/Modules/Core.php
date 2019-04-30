<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Modules;

use Connect\Config;
use Connect\Model;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * Class Client
 * @property Config $config
 * @property LoggerInterface $logger
 * @property ClientInterface $http
 * @package Connect\Modules
 */
abstract class Core
{
    /**
     * The Logger interface
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * The APP Config
     * @var Config
     */
    protected $config;

    /**
     * The HTTP Client interface
     * @var ClientInterface
     */
    protected $http;

    /**
     * Client constructor.
     * @param Config $config
     * @param LoggerInterface $logger
     * @param ClientInterface $http
     */
    public function __construct(Config $config, LoggerInterface $logger, ClientInterface $http)
    {
        $this->setConfig($config);
        $this->setLogger($logger);
        $this->setHttp($http);
    }

    /**
     * Get the logger interface
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set the logger interface
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get the config
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the APP config
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get the Http Client interface
     * @return ClientInterface
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * Set the HTTP client
     * @param ClientInterface $http
     */
    public function setHttp(ClientInterface $http)
    {
        $this->http = $http;
    }

    /**
     * Send the actual request to the connect endpoint
     * @param string $verb
     * @param string $path
     * @param null|Model|string $body
     * @return string
     * @throws GuzzleException
     */
    public function sendRequest($verb, $path, $body = null)
    {
        $this->logger->info('Module: ' . __CLASS__);

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
}
