<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;


class ProductVisibility extends Model
{
    /**
     * @var bool
     */
    public $owner;

    /**
     * @var bool
     */
    public $listing;

    /**
     * @var bool
     */
    public $syndication;

    /**
     * @var bool
     */
    public $catalog;
}