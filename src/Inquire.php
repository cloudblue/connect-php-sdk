<?php

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

    /**
     * Inquire constructor
     * @param Param[] $params - Parameters to inquiry, updated in request
     */
    public function __construct($params)
    {
        $this->params = $params;
        parent::__construct('Activation parameters are required', 'inquiry');
    }
}