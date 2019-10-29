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
     * @var ProductCategory
     */

    protected $category;

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

    public function setCategory($category)
    {
        $this->category = Model::modelize('ProductCategory', $category);
    }

    public function getTemplates()
    {
        if ($this->id == null) {
            return [];
        }
        $body = ConnectClient::getInstance()->directory->sendRequest('GET', '/products/'.$this->id.'/templates');
        return Model::modelize('templates', json_decode($body));
    }

    public function getProductConfigurations(RQL\Query $filter = null)
    {
        if ($this->id == null) {
            return [];
        }
        if (!$filter) {
            $filter = new \Connect\RQL\Query();
        }
        $body = ConnectClient::getInstance()->directory->sendRequest('GET', '/products/'.$this->id.'/configurations'.$filter->compile());
        return Model::modelize('ProductConfigurationParameters', json_decode($body));
    }
}
