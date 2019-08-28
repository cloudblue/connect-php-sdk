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
    protected $configurations;

    /**
     * @var ProductCustomerUISettings
     */
    protected $customer_ui_settings;

    public function setConfigurations($configuration)
    {
        $this->configurations = Model::modelize('ProductConfigurations', $configuration);
    }

    public function setCustomer_ui_settings($ui_settings)
    {
        $this->customer_ui_settings = Model::modelize('ProductCustomerUISettings', $ui_settings);
    }
}
