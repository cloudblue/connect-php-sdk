<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Configuration
 * @package Connect
 */
class Configuration extends Model
{
    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $name;

    /**
     * @var Account
     */

    public $account;

    /**
     * @var Product
     */

    public $product;

    /**
     * @var
     */
    public $tier_level;

    /**
     * @var Connection
     */
    public $connection;

    /**
     * @var Events
     */

    public $events;

    /**
     * @var Param[]
     */
    public $params;

    /**
     * @var OpenRequest
     */
    public $open_request;

    /**
     * @var Template
     */
    public $template;

    /**
     * Return a Param by ID
     * @param $id
     * @return Param
     */
    public function getParameterByID($id)
    {
        $param = current(array_filter($this->params, function (Param $param) use ($id) {
            return ($param->id === $id);
        }));

        return ($param) ? $param : null;
    }
}
