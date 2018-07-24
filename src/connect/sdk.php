<?php 
namespace Connect;

require "logger.php";

class Product
{
	var $id;
	var $name;
}

class Provider
{
	var $id;
	var $name;
}

class Vendor
{
	var $id;
	var $name;
}

class Connection
{
	var $id;
	var $name;
	
	/**
	 * @var Provider
	 */
	var $provider;

	/**
	 * @var Vendor
	 */
	var $vendor;
}

class Item
{
	var $id;
	var $mpn;
	
	/**
	 * @var int
	 */
	var $quantity;

	/**
	 * @var int
	 */
	var $old_quantity;
}

class ValueOption
{
	var $value;
	var $label;
}

class Param
{
	var $id;
	var $name;
	var $description;
	var $value;
	var $value_error;
	
	/**
	 * @var ValueOption{value}
	 */
	var $value_choices;
	
	function error($msg)
	{
		$this->value_error = $msg;
		return $this;
	}
	
	function value($newValue) 
	{
		$this->value = $newValue;
		return $this;
	}
}

class Tier
{
	var $id;
	var $external_id;
	var $name;

    /**
     * @var ContactInfo
     */
	var $contact_info;
}

class Asset
{
	var $id;
	var $external_id;
	
	/**
	 * @var Product
	 */
	var $product;
	
	/**
	 * @var Connection
	 */
	var $connection;
	
	/**
	 * @var Item[]
	 */
	var $items;
	
	/**
	 * @var Param{id}
	 */
	var $params;
	
	/**
	 * @var Tier{}
	 */
	var $tiers;
}

class PhoneNumber
{
	var $country_code;
	var $area_code;
	var $phone_number;
	var $extension;
}

class Contact
{
	var $email;
	var $first_name;
	var $last_name;
	
	/**
	 * @var PhoneNumber
	 */
	var $phone_number;
}

class ContactInfo
{
    var $address_line1;
    var $address_line2;
    var $city;
    
    /**
     * @var Contact
     */
    var $contact;
    var $country;
    var $postal_code;
    var $state;
}

class Request
{
	/**
	 * @var Asset
	 */
	var $asset; 
	
	var $id;
	var $type;
	var $created;
	var $updated;
	var $status;
	
	var $requestProcessor;
	
	function getNewItems()
	{
		$ret = array();
		foreach ($this->asset->items as $item) {
			if (($item->quantity > 0) && ($item->old_quantity == 0))
				$ret[] = $item;
		}
		return $ret;
	}

	function getChangedItems()
	{
		$ret = array();
		foreach ($this->asset->items as $item) {
			if (($item->quantity > 0) && ($item->old_quantity > 0))
				$ret[] = $item;
		}
		return $ret;
	}
	
	function getRemovedItems()
	{
		$ret = array();
		foreach ($this->asset->items as $item) {
			if (($item->quantity == 0) && ($item->old_quantity > 0))
				$ret[] = $item;
		}
		return $ret;
	}
	
}

class RequestsProcessor
{
	private $ch; // curl handle
	private $config;
	
	private 
	function sendRequest($verb, $path, $body = null)
	{
		if (!isset($this->ch)) {
			$this->ch = \curl_init();
			curl_setopt($this->ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($this->ch, CURLOPT_HEADER, true);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
// 			curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, self::$timeout);
// 			curl_setopt($this->ch, CURLOPT_TIMEOUT, self::$timeout);
// 			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
// 			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		
		$requestId = uniqid('api-request-');
		$uri = $this->config['ApiEndpoint'] . $path;
		$headers = array();
		$headers[] = 'Authorization: ApiKey ' . $this->config['ApiKey'];		
		$headers[] = 'Request-ID: '. $requestId;
		$headers[] = 'Content-Type: application/json';
		
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($this->ch, CURLOPT_URL, $uri);
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $verb);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body ? $body : '');
		
		
		$requestHeadersString = join("\n", $headers);

		Logger::get()->info("HTTP Request: $verb $uri");
		if ($requestHeadersString)
			Logger::get()->debug("Request Headers:\n$requestHeadersString");
		if ($body)
			Logger::get()->debug("Request Body:\n$body");
		
		$rawResponse = curl_exec($this->ch);
		$pos = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
		$responseHeadersString = rtrim(substr($rawResponse, 0, $pos));
		$response = substr($rawResponse, $pos);
		$httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		$httpReason = explode(" ", explode("\n", $responseHeadersString, 2)[0], 2)[1]; 
	
		Logger::get()->info("HTTP Code: $httpReason");
		Logger::get()->debug("HTTP Response: ".$rawResponse);
		
		if ($httpCode >= 299) {
			if (isset($response) and '{' == $response[0]) {
				// Try to extract error if exists (JSON only)
				$errorObj = json_decode($response);
				$errorCode = (isset($errorObj->code)) ? $errorObj->code : $httpCode;
				$errorMessage = (isset($errorObj->type)) ? $errorObj->type . ': ' : '';
				$errorMessage .= (isset($errorObj->message)) ? $errorObj->message : '';
		
				throw new Exception($errorMessage, $errorCode, $errorObj);
			}
		
			throw new Exception(
					'HTTP Error code='.$httpCode . ' ' . $response . PHP_EOL . curl_error($this->ch),
					$httpCode
					);
		}
		
		if (curl_errno($this->ch)) {
			throw new Exception(
					sprintf(
							'Connection error: "%s". Was trying to connect to "%s".',
							curl_error($this->ch) . PHP_EOL,
							$uri
							),
					500
					);
		}

		return $response;
	}
	
	private function parse($structure, $className)
	{
		// handle Array
		if (substr($className, -2) === '[]') {
			
			$ret = array();
			foreach($structure as $el) {
				$obj = $this->parse($el, substr($className, 0, -2));
				$ret[] = $obj;
			}
			
			return $ret;
		}

		// handle Map
		if (substr($className, -1) === '}') {
		    $mapTo = null;
			if (preg_match('/{(\w*)}$/', $className, $m)) {
				$mapTo = $m[1];
				$className = substr($className, 0, -strlen($m[0]));
			}
			
			$ret = array();
			foreach($structure as $key => $el) {
				$obj = $this->parse($el, $className);
				if ($mapTo)
					$key = $el[$mapTo];
				$ret[$key] = $obj;
			}
				
			return $ret;
		}
		
		
		$class = '\\Connect\\' . $className;
		$obj = new $class;
		
		$ref = new \ReflectionClass($obj);
		$props = $ref->getProperties();
		
		foreach ($props as $prop) {
			if ($prop->getName() == 'requestProcessor') {
				$prop->setValue($obj, $this);
				continue;
			}
			
			if (isset($structure[$prop->getName()])) {
				$value = $structure[$prop->getName()];
				if ($prop->getDocComment()) {
					$comment = trim($prop->getDocComment());
					$m = null;
					if (preg_match('/\@var\s+(\S+)/', $comment, $m)) {
						$subClassName = $m[1];
						switch($subClassName) {
							case 'int':
								$value = ($value == '') ? null : (int)$value;
								break;
							case 'string':
								break;
							case 'bool':
								$value = (bool)$value;
								break;
							default:
								$subClass = '\\Connect\\' . $subClassName;
								$value = $this->parse($value, $subClassName);
								break;
						}
					}
				} 
				$prop->setValue($obj, $value);
			}
		}
		
		return $obj;
	}
	
	public
	function __construct($config)
	{
		$this->config = $config;
	}
	
	public
	function processRequest($req)
	{
		throw new \Exception('processRequest() method is not implemented');
	}
	
	public
	function process()
	{
		$reqstr = $this->sendRequest('GET', '/requests');
		$reqarr = json_decode($reqstr, true);
		
		$reqlist = $this->parse($reqarr, 'Request[]');

		foreach($reqlist as $req) {
			if ($req->status == 'pending') { // actually default filter is pending 
				try {
					$msg = $this->processRequest($req);
				
					if (!$msg)
						$msg = 'Activation succeeded';
					
					// ok now make request completed
					$this->sendRequest('POST', '/requests/'.$req->id.'/approve', '{"activation_tile": "'.$msg.'"}');
				} catch (Inquire $e) {
					// update parameters and move to inquire
					$this->updateParameters($req, $e->params);
					$this->sendRequest('POST', '/requests/'.$req->id.'/inquire', '{}');
				} catch (Fail $e) {
					// fail request
					$this->sendRequest('POST', '/requests/'.$req->id.'/fail', '{"reason": "'.$e->getMessage().'"}');
				} catch (Skip $e) {
				    Logger::get()->debug("Skipping ".$req->id);
                }
			}
		}
	}
	
	function updateParameters($req, $parray)
	{
		$plist = array();
		foreach ($parray as $p) {
			$parr = (array)$p;
			unset($parr['value_choices']);
			$plist[] = $parr;
		}
	
		$body = json_encode(array('asset' => array('params' => $plist)), JSON_PRETTY_PRINT);
		$this->sendRequest('PUT', '/requests/'.$req->id, $body);
	}
	
}

class Exception extends \Exception
{
	var $object;
	
	public
	function __construct($message, $code, $object = null)
	{
		$this->code = $code;
		$this->message = $message;
		$this->object = $object;
	}
}

class Inquire extends Exception
{
	var $params;
	
	public
	function __construct($params)
	{
		$this->params = $params;
		parent::__construct('Activation parameters are required', 'inqury');
	}
}

class Skip extends Exception
{
    public
    function __construct($message = null)
    {
        parent::__construct($message ? $message : 'Request skipped', "skip");
    }
}

class Fail extends Exception
{
	public
	function __construct($message = null)
	{
		parent::__construct($message ? $message : 'Request processing failed', 'fail');
	}
}


?>