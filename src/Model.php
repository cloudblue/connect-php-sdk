<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Doctrine\Common\Inflector\Inflector;


/**
 * Class Model
 * @package Connect
 */
class Model implements \ArrayAccess
{
    /**
     * Required properties, if the object is instantiated without
     * the properties listed here the model will
     * @var array
     */
    protected $_required = [];

    /**
     * List of protected properties, this only apply for logging
     * and/or debugging process, the fields listed here will being
     * replaced with ******** instead the real value. Useful to hide
     * passwords or sensible data.
     * @var array
     */
    protected $_protected = [];

    /**
     * The hidden properties, internal usage only, more properties can
     * been added overriding this property in inherit.
     * @var array
     */
    protected $_hidden = [
        '_required',
        '_protected',
        '_hidden',
    ];

    /**
     * Model constructor.
     * @param object|array $source
     */
    public function __construct($source = null)
    {
        if (is_object($source) || is_array($source) && !empty($source)) {
            $this->hydrate($source);
        }
    }

    /**
     * Return the required fields
     * @return array
     */
    public function getRequired()
    {
        return $this->_required;
    }

    /**
     * Return the protected fields
     * @return array
     */
    public function getProtected()
    {
        return $this->_protected;
    }

    /**
     * Return the hidden fields
     * @return array
     */
    public function getHidden()
    {
        return $this->_hidden;
    }

    /**
     * Populate the object recursively
     * @param array|object $source
     * @return $this
     */
    public function hydrate($source)
    {
        /**
         * foreach element of the "source" try to find programmatically
         * the correct data model base in the property type and name.
         */
        foreach ($source as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Set the given value into the model
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $allowed = array_diff(array_keys(get_class_vars(get_class($this))), $this->_hidden);
        if (empty($allowed) || !empty($allowed) && in_array($key, $allowed)) {
            if (method_exists($this, 'set' . ucfirst($key))) {
                call_user_func_array([$this, 'set' . ucfirst($key)], [$value]);
            } else {
                $this->{$key} = self::modelize($key, $value);
            }
        }

        return $this;
    }

    /**
     * Set the given value into the model (magic)
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Get the requested value from the model
     * @param string $key
     * @return null|mixed
     */
    public function get($key)
    {
        $allowed = array_diff(array_keys(get_class_vars(get_class($this))), $this->_hidden);
        if (empty($allowed) || !empty($allowed) && in_array($key, $allowed)) {

            if (method_exists($this, 'get' . ucfirst($key))) {
                return call_user_func_array([$this, 'get' . ucfirst($key)], [$key]);
            }

            return $this->{$key};
        }

        return null;
    }

    /**
     * Get the requested value from the model (magic)
     * @param string $key
     * @return null|mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Modelize the given value into \Connect\Model
     * @param string $key
     * @param mixed $value
     * @return array|Model
     */
    public static function modelize($key, $value)
    {
        switch (gettype($value)) {
            case 'object':

                /**
                 * if the item is an object search if there are any model that match with it:
                 *
                 *  1. fqcn = base namespace + capitalized property name.
                 *
                 *  2. fqcn = base namespace + capitalized + singularized property name.
                 *
                 *  3. if '_' is found in name then
                 *      fqcn = base namespace + split property name by '_', for each section
                 *             capitalize, singularize and merge sections.
                 *
                 *  4. fqcn = base namespace + capitalized + singularized property name with trimmed nums.
                 *
                 *  if any of the user cases from above match, create a single Connect\Model object.
                 */
                $fqcn = '\Connect\\' . ucfirst($key);
                if (class_exists($fqcn, true)) {
                    return new $fqcn($value);
                }
                $fqcn = '\Connect\\' . ucfirst(Inflector::singularize($key));
                if (strpos($key, '_') !== false) {
                    $fqcn = '\Connect\\' . implode('', array_map(function ($word) {
                            return ucfirst(Inflector::singularize($word));
                        }, explode('_', $key)));
                }
                if (class_exists($fqcn, true)) {
                    return new $fqcn($value);
                }
                $fqcn = trim($fqcn, '0123456789');
                if (class_exists($fqcn, true)) {
                    return new $fqcn($value);
                }
                $fqcn = '\Connect\\Usage\\' . ucfirst($key);
                if (class_exists($fqcn, true)) {
                    return new $fqcn($value);
                }
                $fqcn = '\Connect\\Usage\\' . ucfirst(Inflector::singularize($key));
                if (strpos($key, '_') !== false) {
                    $fqcn = '\Connect\\Usage\\' . implode('', array_map(function ($word) {
                            return ucfirst(Inflector::singularize($word));
                        }, explode('_', $key)));
                }
                if (class_exists($fqcn, true)) {
                    return new $fqcn($value);
                }
                

                return new \Connect\Model($value);

                break;
            case 'array':

                $array = [];

                /**
                 * if the item is an array, call again the modelize method for each item
                 * of the array using the key as seed of the model and  keeping the index
                 * of original array.
                 */
                foreach ($value as $index => $item) {
                    $array[$index] = self::modelize(is_int($index) ? $key : $index, $item);
                }

                return $array;

                break;
            default:

                /**
                 * if the data is an scalar directly assign it. the data type
                 * should be correctly from the source: int as int instead
                 * of string etc...
                 */
                return $value;
        }
    }

    /**
     * Transform the given structure into array
     * @param mixed $value
     * @return array
     */
    public static function arrayize($value)
    {
        $forbidden = [];
        if ($value instanceof \Connect\Model) {
            $forbidden = $value->getHidden();
        }

        $array = [];
        foreach ($value as $key => $item) {
            if (!in_array(trim($key), $forbidden)) {
                switch (gettype($item)) {
                    case 'object':
                    case 'array':
                        $buffer = self::arrayize($item);
                        if (!empty($buffer)) {
                            $array[trim($key)] = $buffer;
                        }

                        break;
                    default:
                        if (isset($item)) {
                            $array[trim($key)] = $item;
                        }
                }
            }
        }

        return $array;
    }

    /**
     * Check if the required properties exists
     * @return array List of missing properties
     */
    public function validate()
    {
        return array_values(array_filter($this->_required, function ($required) {
            return empty($this->{$required});
        }));
    }

    /**
     * Transform the \Connect\Model into array
     * @return array
     */
    public function toArray()
    {
        return self::arrayize($this);
    }

    /**
     * Return the object in json format
     * @param boolean $pretty
     * @return string
     */
    public function toJSON($pretty = false)
    {
        return json_encode($this->toArray(), $pretty ? JSON_PRETTY_PRINT : null);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->{$offset})
            ? $this->get($offset)
            : null;
    }

    /**
     * Debug method
     * @return array
     */
    public function __debugInfo()
    {
        $debug = [];
        foreach ($this as $key => $value) {
            if (in_array($key, $this->_protected)) {
                $debug[$key] = '********************************';
            } else {
                if (!in_array($key, $this->_hidden)) {
                    $debug[$key] = $value;
                }
            }
        }
        return $debug;
    }
}