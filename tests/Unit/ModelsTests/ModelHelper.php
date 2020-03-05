<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Connection;
use Connect\Item;
use Connect\Model;
use Connect\ValueChoice;

/**
 * Class ModelHelper
 * @property string $isAdmin
 * @package Test\Unit
 */
class ModelHelper extends Model
{
    /**
     * The protected list of properties
     * @var array
     */
    protected $_protected = [
        'password'
    ];

    /**
     * The list of required fields
     * @var array
     */
    protected $_required = [
        'id',
        'username',
        'password',
    ];

    /**
     * Unique identifier
     * @var string
     */
    public $id;

    /**
     * Model username
     * @var string
     */
    public $username;

    /**
     * Model password
     * @var string
     */
    public $password;

    /**
     * Is admin flag
     * @var boolean
     */
    protected $isAdmin;

    /**
     * List of items
     * @var Item[]
     */
    public $items = [];

    /**
     * List of ValueChoices
     * @var ValueChoice[]
     */
    public $value_choices = [];

    /**
     * List of vectors (just random data)
     * @var Model[]
     */
    public $vectors = [];

    /**
     * Sub set of tears
     * @var object
     */
    public $tiers;

    /**
     * Connection object
     * @var Connection
     */
    public $connection;

    /**
     * Return the handled field.
     * @return string
     */
    public function getIsAdmin()
    {
        return ($this->isAdmin) ? 'yes' : 'no';
    }

    /**
     * Set the value
     * @param $value
     */
    public function setIsAdmin($value)
    {
        switch (gettype($value)) {
            case 'boolean':
                $this->isAdmin = $value;

                break;
            case 'string':

                $options = ['yes' => true, 'no' => false];
                if (array_key_exists($value, $options)) {
                    $this->isAdmin = $options[$value];
                } else {
                    throw new \InvalidArgumentException("Invalid value " . $value . " must be one of " . implode(
                        ", ",
                        array_keys($options)
                    ));
                }

                break;

            default:
                throw new \InvalidArgumentException("Wrong argument type " . gettype($value) . " for isAdmin in " . __CLASS__);
        }
    }
}
