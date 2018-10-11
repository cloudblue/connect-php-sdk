<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Config
 * @property string $apiKey
 * @property string $apiEndpoint
 * @property array $runtimeServices
 * @package Connect
 */
class Config extends Model
{
    /**
     * Connect QuickStart API Key
     * @var string
     */
    protected $apiKey;

    /**
     * Connect QuickStart API Endpoint URI
     * @var string
     */
    protected $apiEndpoint;

    /**
     * List of products to work with
     * @var string[]
     */
    public $products;

    /**
     * What messages to write to log (legacy)
     * @var int
     */
    public $logLevel = 2;

    /**
     * Enable the debug mode
     * @var bool
     */
    public $debug = false;

    /**
     * Network interaction timeout, seconds
     * @var int
     */
    public $timeout = 50;

    /**
     * Do we need to verify SSL certificate of server
     * @var bool
     */
    public $sslVerifyHost = true;

    /**
     * List of runtime services
     * @var array
     */
    protected $runtimeServices = [
        'logger' => '\Connect\Runtime\Providers\LoggerServiceProvider',
        'http' => '\Connect\Runtime\Providers\HttpServiceProvider',
    ];

    /**
     * @param array|object|string $source
     *        array  -> has pairs of key/value to fill in config
     *        string -> path to file to read config from
     *
     * @throws ConfigException
     */
    public function __construct($source)
    {
        switch (gettype($source)) {
            case 'string':

                if (!is_readable($source)) {
                    throw new ConfigException("Can't read file $source");
                }
                $source = json_decode(file_get_contents($source), true);
                if (!isset($source)) {
                    throw new ConfigException("Can't parse JSON config file.");
                }
                break;
            case 'array':
                break;
            default :
                throw new ConfigException("Invalid argument for \\Connect\\Config class constructor: " . gettype($source));
        }

        parent::__construct($source);
    }

    /**
     * Validate and set the API Key property
     * @param string $value
     * @throws ConfigPropertyMissed
     */
    public function setApiKey($value)
    {
        if (empty($value)) {
            throw new ConfigPropertyMissed("Missing required property apiKey.");
        }
        $this->apiKey = trim($value);
    }

    /**
     * Validate and set the API Endpoint property
     * @param string $value
     * @throws ConfigPropertyMissed
     */
    public function setApiEndpoint($value)
    {
        if (empty($value)) {
            throw new ConfigPropertyMissed("Missing required property apiEndpoint.");
        }

        $this->apiEndpoint = rtrim($value, "/");
    }

    /**
     * Reconfigure the service builder list
     * @param array $runtimeServices
     */
    public function setRuntimeServices($runtimeServices)
    {
        if (!is_array($runtimeServices)) {
            throw new \InvalidArgumentException("The service provider list must be an array, given " . gettype($runtimeServices));
        }

        $this->runtimeServices = array_merge($this->runtimeServices, $runtimeServices);
    }
}
