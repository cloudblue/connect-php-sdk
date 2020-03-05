<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Constraints
 * @package Connect
 */
class Constraints extends Model
{
    /**
     * @var boolean
     */
    public $hidden;
    /**
     * @var boolean
     */
    public $required;

    /**
     * @var Choice[]
     */
    public $choices;

    /**
     * @var boolean
     */
    public $unique;
}
