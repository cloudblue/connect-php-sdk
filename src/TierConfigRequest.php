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
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $status;


    /**
     * @var Configuration
     */

    public $configuration;

    /**
     * @var Events
     */

    public $events;

    /**
     * @var Param[]
     */
    public $params;

    /**
     * @var Assignee
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
     * Return a Param by ID
     * @param $id
     * @return Param
     */
    public function getParameterByID($id)
    {
        $param = current(array_filter($this->params, function (Param $param) use ($id) {
            return ($param->id === $id);
        }));

        return ($param) ? $param : null;
    }
}