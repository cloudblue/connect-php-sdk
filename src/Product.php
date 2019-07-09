<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Product
 * @package Connect
 */
class Product extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string|null
     */
    public $icon;

    /**
     * @var string
     */
    public $short_description;

    /**
     * @var string
     */
    public $detailed_description;

    /**
     * @var string
     */

    public $published_at;

    /**
     * @var int
     */
    public $version;

    /**
     * @var ProductConfiguration
     */
    public $configurations;

    /**
     * @var ProductCustomerUISettings
     */
    public $customer_ui_settings;

    public function __construct($source = null)
    {
        parent::__construct($source);
        $this->configurations = isset($source->configurations) ? Model::modelize('ProductConfigurations', $source->configurations): $this->configurations;
        $this->customer_ui_settings = isset($source->customer_ui_settings) ? Model::modelize('ProductCustomerUISettings', $source->customer_ui_settings): $this->customer_ui_settings;
    }
}
