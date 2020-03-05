<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

use Connect\Model;

class FileUsageCategory extends Model
{
    /**
     * @var string
     */
    public $category_id;

    /**
     * @var string
     */
    public $category_name;

    /**
     * @var string
     */
    public $category_description;
}
