<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class TierAccount
 * @package Connect
 */
class TierAccount extends Model
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $environment;

    /**
     * @var string
     */

    public $name;

    /**
     * @var string
     */
    public $external_id;

    /**
     * @var string
     */
    public $external_uid;

    /**
     * @var \Connect\Events
     */
    public $events;

    /**
     * @var string[]
     */
    public $scopes;

    /**
     * @var \Connect\Marketplace
     */
    public $marketplace;

    /**
     * @var \Connect\Hub
     */
    public $hub;

    /**
     * @var \Connect\ContactInfo
     */
    public $account_info;

    public function __construct($source = null)
    {
        parent::__construct($source);
        if(is_null($this->account_info))
        {
            $tempAccount = ConnectClient::getInstance()->directory->sendRequest('GET', '/tier/accounts/'.$this->id);
            $this->account_info = \Connect\Model::modelize('ContactInfo', json_decode($tempAccount)->contact_info);
        }
    }

}