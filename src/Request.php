<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Request
 *      APS Connect QuickStart Request object
 * @package Connect
 */
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

    /**
     * @var RequestsProcessor
     * @noparse
     */
    var $requestProcessor;

    /**
     * Get new SKUs purchased in the request
     * @return Item[]
     */
    function getNewItems()
    {
        $ret = array();
        foreach ($this->asset->items as $item) {
            if (($item->quantity > 0) && ($item->old_quantity == 0))
                $ret[] = $item;
        }
        return $ret;
    }

    /**
     * Get SKUs upgraded in the request
     * @return Item[]
     */
    function getChangedItems()
    {
        $ret = array();
        foreach ($this->asset->items as $item) {
            if (($item->quantity > 0) && ($item->old_quantity > 0))
                $ret[] = $item;
        }
        return $ret;
    }

    /**
     * Get SKUs removed in the request
     * @return Item[]
     */
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