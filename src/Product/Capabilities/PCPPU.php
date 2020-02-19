<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Product\Capabilities;


use Connect\Model;

class PCPPU extends Model
{
    /**
     * @var string
     */
    public $schema;

    /**
     * @var bool
     */
    public $dynamic;

    /**
     * @var bool
     */
    public $predictive;
}