<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

use Connect\Model;

class Stats extends Model
{
    /**
     * @var string | null
     */
    public $uploaded;

    /**
     * @var string | null
     */
    public $validated;

    /**
     * @var string | null
     */
    public $pending;

    /**
     * @var string | null
     */
    public $accepted;

    /**
     * @var string | null
     */
    public $closed;
}
