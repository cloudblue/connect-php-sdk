<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Item
 * @package Connect
 */
class Item extends Model
{
    public $display_name;
    public $global_id;
    public $id;
    public $item_type;
    public $mpn;
    public $period;
    public $type;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var int
     */
    public $old_quantity;

    /**
     * @var Renewal
     */
    public $renewal;
}
