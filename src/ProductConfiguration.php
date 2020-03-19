<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ProductConfiguration
 * @package Connect
 */
class ProductConfiguration extends Model
{
    /**
     * @var boolean
     */
    public $suspend_resume_supported;

    /**
     * @var boolean
     */
    public $requires_reseller_information;
}
