<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Product\Capabilities;

use Connect\Model;
use Connect\TierConfig;

class PCTiers extends Model
{
    /**
     * @var TierConfig | null
     */
    public $configs;

    /**
     * @var int
     */
    public $level;

    /**
     * @var bool
     */
    public $updates;
}
