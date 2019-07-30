<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class TierConfigRequest
 *      APS Connect Tier Config Request object
 * @package Connect
 */
class TierConfigRequest extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $status;


    /**
     * @var TierConfig
     */

    protected $configuration;

    /**
     * @var Events
     */

    public $events;

    /**
     * @var Param[]
     */
    public $params;

    /**
     * @var User
     */
    public $assignee;

    /**
     * @var Template
     */
    public $template;

    /**
     * @var Activation
     */
    public $activation;

    /**
     * @var string
     */
    public $environment;

    /**
     * @var Tiers
     */
    public $tiers;

    /**
     * @var Marketplace
     */
    public $marketplace;

    /**
     * @var Contract
     */
    public $contract;

    /**
     * Return a Param by ID
     *
     * @param string $id
     * @return Param
     */
    public function getParameterByID($id)
    {
        $param = current(array_filter($this->params, function (Param $param) use ($id) {
            return ($param->id === $id);
        }));

        return ($param) ? $param : null;
    }

    public function setConfiguration($configuration)
    {
        $this->configuration = Model::modelize('TierConfig', $configuration);
    }
}
