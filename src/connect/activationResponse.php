<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

require_once "exception.php";

/**
 * Class activationResponse
 * @package Connect
 */
class activationResponse
{

    /**
     * @var null|string
     */
    public $activationTile = "Activation succeeded";

    /**
     * @var null
     */
    public $templateId;

    /**
     * activationResponse constructor.
     * @param null $activationTile
     * @param null $templateId
     */
    public function __construct($responseActivation = null)
    {
        if($responseActivation != null){
            if(substr( $responseActivation, 0, 3 ) == "TL-")
            {
                $this->templateId = $responseActivation;
            }
            else
            {
                $this->activationTile = $responseActivation;
            }
        }
    }

    /**
     * @return bool
     * @throws activationResponseException
     */

    public function validate()
    {
        if(!is_string($this->activationTile)){
            throw new activationResponseException("Value for activationTile is not valid");
        }
        if(strlen($this->activationTile) >= 4096)
        {
            throw new activationResponseException("Value of activationTile is too long, maximum length is 4K characters");
        }
        if(($this->templateId != null) && (substr( $this->templateId, 0, 3 ) != "TL-"))
        {
            throw new activationResponseException("TemplateId parameter is invalid");
        }
        return true;
    }

    public function forActivate()
    {
        if($this->validate())
        {
            if($this->templateId != null)
            {
                return json_decode('{"template_id": "'.$this->templateId.'"}');
            }
            else
            {
                return json_decode('{"activation_tile": "'.$this->activationTile.'"}');
            }
        }
    }
}

/**
 * Class activationResponseException
 * @package Connect
 */
class activationResponseException extends Exception
{
    /**
     * activationResponseException constructor.
     * @param $message
     */
    public function __construct($message)
    {
        parent::__construct('Value for '.$prop. 'is not valid.');
    }
}