<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
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
    public $contact_info;

    /**
     * @var int
     */
    public $version=1;

    /**
     * TierAccount constructor.
     * @param null $source
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function __construct($source = null)
    {
        parent::__construct($source);
        if (is_null($this->contact_info) && $this->id) {
            $tempAccount = ConnectClient::getInstance()->directory->sendRequest('GET', '/tier/accounts/'.$this->id);
            $this->contact_info = \Connect\Model::modelize('ContactInfo', json_decode($tempAccount)->contact_info);
        }
    }

    public function diffWithPreviousVersion($version = null)
    {
        $treeWalker = new \TreeWalker(
            array(
                "returntype" => "array"
            )
        );
        if ($version == null) {
            $version = $this->version - 1;
        }
        $version = ConnectClient::getInstance()->directory->sendRequest(
            'GET',
            '/tier/accounts/' . $this->id . '/versions/' . $version
        );
        $diff = $treeWalker->getdiff(json_decode($this->toJSON()), json_decode($version), true);
        unset($diff['edited']['version']);
        return $diff;
    }
}
