<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
*
* @copyright (c) 2018. Ingram Micro. All Rights Reserved.
*/

namespace Connect;

require_once "exception.php";
require_once "logger.php";

class Config 
{
	/**
	 * @var - string Connect QuickStart API Key
	 */
	public $apiKey;

	/**
	 * @var string - Connect QuickStart API Endpoint URI
	 */
	public $apiEndpoint;
	
	/**
	 * @var array of strings - list of products to work with
	 */
	public $products;
	
	/**
	 * @var int - logLevel - what messages to write to log
	 */
	public $logLevel = LoggerInterface::LEVEL_INFO;

	/**
	 * @var int - network interaction timeout, seconds
	 */
	public $timeout = 50;
	
	/**
	 * @var bool - do we need to verify SSL certificate of server
	 */
	public $sslVerifyHost = true;

    /**
     * @param mixed $config -
     *        array  -> has pairs of key/value to fill in config
     *        string -> path to file to read config from
     * @throws ConfigException
     * @throws ConfigPropertyInvalid
     * @throws \ReflectionException
     */
	public function __construct($config)
	{
		if (is_string($config)) {
			try {
				$txt = file_get_contents($config);
			} catch (\Exception $e) {
				throw new ConfigException("Can't read file $config: " . $e->getMessage());
			}
			
			try {				
				$config = json_decode($txt, true);
			} catch (\Exception $e) {
				throw new ConfigException("Can't parse JSON in file $config: " . $e->getMessage());
			}
		}
		
		if (!is_array($config))
			throw new ConfigException("Invalid argument for \\Connect\\Config class constructor: " . gettype($config));
				
		$ref = new \ReflectionClass($this);
		foreach ($ref->getProperties() as $prop) {
			$name = $prop->getName();		

			if (!isset($config[$name]))
				continue;

			$value = $config[$name];
			
			if ($name == 'products') {
				$prop->setValue($this, is_array($value) ? $value : array($value));
			} elseif ($name == 'logLevel') {
				$found = false;
				foreach (LoggerInterface::LEVELS as $k => $v) {
					if (strtoupper($value) == $v) {
						$prop->setValue($this, $k);
						$found = true;
					}
				}
				if (!$found)
					throw new ConfigPropertyInvalid('Unknown log level', $name, $value);
			} elseif ($name == "sslVerifyHost") {
				if (!is_bool($value))
					throw new ConfigPropertyInvalid('Should be boolean', $name, $value);
				$prop->setValue($this, $value);
		    } else {
				$prop->setValue($this,$value);
			}
		}
	}

    /**
     * Validate configuration
     * @throws ConfigPropertyMissed
     */
    public function validate()
	{
		if (!isset($this->apiKey))
			throw new ConfigPropertyMissed('apiKey');
		
		if (!isset($this->apiEndpoint))
			throw new ConfigPropertyMissed('apiEndpoint');
					
	}
}

/**
 * Class ConfigException
 *      Generic configuration exception
 *
 * @package Connect
 */
class ConfigException extends Exception
{
	private $property; 
	
	public function __construct($message, $prop = null)
	{
        parent::__construct($message, 'config');
        $this->property = $prop;
		
		if ($prop)
			$this->message = $message . " for property " . $prop;
	}
}

/**
 * Class ConfigPropertyMissed
 *      Configuration property missed exception
 *
 * @package Connect
 */
class ConfigPropertyMissed extends ConfigException
{
	public function __construct($prop)
	{
		parent::__construct('Value is not set ', $prop);
	}
}

/**
 * Class ConfigPropertyInvalid
 *      Configuration property invalid exception
 * @package Connect
 */
class ConfigPropertyInvalid	 extends ConfigException
{
	public function __construct($message, $prop, $value)
	{
		parent::__construct("Invalid property value '$value' " . $message, $prop);
	}
}