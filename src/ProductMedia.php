<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

class ProductMedia extends Model

{
    /**
     * @var int
     */
    public $position;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     * possible values are video, image
     */
    public $type;

    /**
     * @var string
     */
    public $thumbnail;

    /**
     * @var string
     */
    public $url;

}