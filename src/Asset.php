<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Asset
 * @package Connect
 */
class Asset extends Model
{
    public $id;
    public $external_id;
    public $external_uid;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var Connection
     */
    public $connection;

    /**
     * @var Item[]
     */
    public $items;

    /**
     * @var Param[]
     */
    public $params;

    /**
     * @var Tiers
     */
    public $tiers;

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



    /**
     * Return a Item by ID
     * @param $id
     * @return Item
     */
    public function getItemByID($id)
    {
        $item = current(array_filter($this->items, function (Item $item) use ($id) {
            return ($item->id === $id);
        }));

        return ($item) ? $item : null;
    }

    /**
     * Return a Item by MPN
     * @param $id
     * @return Item
     */
    public function getItemByMPN($mpn)
    {
        $item = current(array_filter($this->items, function (Item $item) use ($mpn) {
            return ($item->mpn === $mpn);
        }));

        return ($item) ? $item : null;
    }
}