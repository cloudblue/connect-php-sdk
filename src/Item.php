<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Item
 * @package Connect
 */
class Item extends Model
{
    /**
     * @var string
     * @deprecated
     */
    public $display_name;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $global_id;
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $item_type;
    /**
     * @var string
     */
    public $mpn;
    /**
     * @var string
     */
    public $period;
    /**
     * @var string
     */
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

    /**
     * @var Param[]
     */

    public $params;

    /**
     * @var Unit
     */

    public $unit;

    /**
     * @var string
     */

    public $description;

    /**
     * @var \stdClass
     */
    public $commitment;

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
}
