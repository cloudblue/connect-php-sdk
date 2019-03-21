<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ActivationTemplateResponse
 * @package Connect
 */
class ActivationTemplateResponse
{
    /**
     * @var
     */
    public $templateid;

    /**
     * ActivationTemplateResponse constructor.
     * @param $templateid
     */
    public function __construct($templateid)
    {
        $this->templateid = $templateid;
    }
}
