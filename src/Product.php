<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Connect\Product\Actions\Action;

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
     * @var string
     */
    public $status;

    /**
     * @var ProductConfiguration
     */
    protected $configurations;

    /**
     * @var ProductCategory
     */

    protected $category;

    /**
     * @var Owner
     */
    public $owner;

    /**
     * @var ProductCustomerUISettings
     */
    protected $customer_ui_settings;

    /**
     * @var ProductStats
     */

    protected $stats;

    /**
     * @var ProductMedia
     */

    protected $media;

    /**
     * @var ProductVisibility
     */
    protected $visibility;

    /**
     * @var ProductCapabilities
     */
    protected $capabilities;

    /**
     * @var bool
     */
    public $latest;

    public function setCapabilities($capabilities)
    {
        $this->capabilities = Model::modelize('ProductCapabilities', $capabilities);
    }

    public function setVisibility($visibility)
    {
        $this->visibility = Model::modelize('ProductVisibility', $visibility);
    }

    public function setMedia($media)
    {
        $this->media = Model::modelize('ProductMedia', $media);
    }

    public function setStats($stats)
    {
        $this->stats = Model::modelize('ProductStats', $stats);
    }

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

    /**
     * @return array|Template
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplates()
    {
        if ($this->id == null) {
            return [];
        }
        $body = ConnectClient::getInstance()->directory->sendRequest('GET', Constants::PRODUCTS_PATH  . $this->id . '/templates');
        return Model::modelize('Templates', json_decode($body));
    }

    /**
     * @param RQL\Query|null $filter
     * @return array|ProductConfigurationParameter
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProductConfigurations(RQL\Query $filter = null)
    {
        if ($this->id == null) {
            return [];
        }
        if (!$filter) {
            $filter = new \Connect\RQL\Query();
        }
        $body = ConnectClient::getInstance()->directory->sendRequest(
            'GET',
            Constants::PRODUCTS_PATH . $this->id . '/configurations' . $filter->compile()
        );
        return Model::modelize('ProductConfigurationParameters', json_decode($body));
    }

    /**
     * @param RQL\Query|null $filter
     * @return array|ProductMedia
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllMedia(RQL\Query $filter = null)
    {
        if ($this->id == null) {
            return [];
        }
        if (!$filter) {
            $filter = new \Connect\RQL\Query();
        }
        $body = ConnectClient::getInstance()->directory->sendRequest(
            'GET',
            Constants::PRODUCTS_PATH  . $this->id . '/media' . $filter->compile()
        );
        return Model::modelize('ProductMedias', json_decode($body));
    }

    /**
     * @param RQL\Query|null $filter
     * @return array|Item
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllItems(RQL\Query $filter = null)
    {
        if ($this->id == null) {
            return [];
        }
        if (!$filter) {
            $filter = new \Connect\RQL\Query();
        }
        $body = ConnectClient::getInstance()->directory->sendRequest(
            'GET',
            Constants::PRODUCTS_PATH  . $this->id . '/items' . $filter->compile()
        );
        return Model::modelize('Items', json_decode($body));
    }

    /**
     * @param RQL\Query|null $filter
     * @return array|Agreement
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllAgreements(RQL\Query $filter = null)
    {
        if ($this->id == null) {
            return [];
        }
        if (!$filter) {
            $filter = new \Connect\RQL\Query();
        }
        $body = ConnectClient::getInstance()->directory->sendRequest(
            'GET',
            Constants::PRODUCTS_PATH  . $this->id . '/agreements' . $filter->compile()
        );
        return Model::modelize('Agreements', json_decode($body));
    }

    /**
     * @return array|Action
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllActions()
    {
        if ($this->id == null) {
            return [];
        }
        $body = ConnectClient::getInstance()->directory->sendRequest(
            'GET',
            Constants::PRODUCTS_PATH  . $this->id . '/actions'
        );
        return Model::modelize('Actions', json_decode($body));
    }
}
