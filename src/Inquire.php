<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Inquire
 *      Inquire for some more data from customer
 *      request to be moved into inquire status
 * @package Connect
 */
class Inquire extends Message
{
    public $params;

    public $templateId;

    /**
     * Inquire constructor
     * @param Param[] $params - Parameters to inquiry, updated in request
     * @param ActivationTemplateResponse $templateId - template id used for inquiring
     */
    public function __construct($params, $templateId = null)
    {
        $this->params = $params;
        $this->templateId = $templateId;
        parent::__construct('Activation parameters are required', 'inquiry');
    }
}
