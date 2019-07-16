<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Asset
 * @package Connect
 */
class Asset extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $status;
    /**
     * @var Events[]
     */
    public $events;
    /**
     * @var string | null
     */
    public $external_id;
    /**
     * @var string | null
     */
    public $external_uid;
    /**
     * @var string | null
     */
    public $external_name;

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
     * @var Contract
     */
    public $contract;

    /**
     * @var Marketplace
     */
    public $marketplace;

    /**
     * Return a Param by ID
     * @param string $id
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
     * @param string $id
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
     * @param string $mpn
     * @return Item
     */
    public function getItemByMPN($mpn)
    {
        $item = current(array_filter($this->items, function (Item $item) use ($mpn) {
            return ($item->mpn === $mpn);
        }));

        return ($item) ? $item : null;
    }

    /**
     * Return a Item by Global ID
     * @param string $global_id
     * @return Item
     */
    public function getItemByGlobalID($global_id)
    {
        $item = current(array_filter($this->items, function (Item $item) use ($global_id) {
            return ($item->global_id === $global_id);
        }));

        return ($item) ? $item : null;
    }

    /**
     * @return Request[]
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRequests()
    {
        if ($this->id == null) {
            return [];
        }
        $body = ConnectClient::getInstance()->directory->sendRequest('GET', '/assets/'.$this->id.'/requests');
        return Model::modelize('requests', json_decode($body));
    }
}
