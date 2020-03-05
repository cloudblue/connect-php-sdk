<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Config;

/**
 * Class VaultConfig
 * @property string $token
 * @property string $uri
 * @property string $path
 * @package IngramMicro\Vault
 */
class VaultConfig extends \Connect\Config
{
    /**
     * Vault authentication Token
     * @var string
     */
    protected $token;

    /**
     * Vault URL
     * @var string
     */
    protected $uri;

    /**
     * Path on the vault where to obtain the config
     * @var string
     */
    protected $path;

    /**
     * Required properties
     * @var array
     */
    protected $_required = [
        'token',
        'uri',
        'path',
    ];

    /**
     * Protected properties
     * @var array
     */
    protected $_protected = [
        'token'
    ];

    /**
     * VaultConfig constructor.
     * @param array|object|string $source
     *        array  -> has pairs of key/value to fill in config
     *        string -> path to file to read config from
     * @param \GuzzleHttp\Client|null $client
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct($source, \GuzzleHttp\Client $client = null)
    {
        parent::__construct($source);

        $missing = $this->validate();
        if (count($missing) > 0) {
            throw new \InvalidArgumentException("Missing properties: " . implode(', ', $missing) . '.');
        }

        if (!isset($client)) {
            $client = new \GuzzleHttp\Client();
        }

        $response = $client->request('GET', trim($this->uri . $this->path), [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception("Error obtaining configuration.");
        }

        $buffer = json_decode($response->getBody());
        if (!isset($buffer->data->data)) {
            throw new \Exception("Wrong formatted configuration obtained from Vault.");
        }

        parent::__construct($buffer->data->data);
    }

    /**
     * Validate and set the API Endpoint property
     * @param string $token
     * @throws \InvalidArgumentException
     */
    public function setToken($token)
    {
        if (empty($token)) {
            throw new \InvalidArgumentException("Missing required property token.");
        }

        $this->token = trim($token);
    }

    /**
     * Validate and set the API Endpoint property
     * @param string $uri
     * @throws \InvalidArgumentException
     */
    public function setUri($uri)
    {
        if (empty($uri)) {
            throw new \InvalidArgumentException("Missing required property uri.");
        }

        $this->uri = rtrim($uri, "/");
    }

    /**
     * Validate and set the path property
     * @param string $path
     * @throws \InvalidArgumentException
     */
    public function setPath($path)
    {
        if (empty($path)) {
            throw new \InvalidArgumentException("Missing required property path.");
        }

        $this->path = trim($path);
    }
}
