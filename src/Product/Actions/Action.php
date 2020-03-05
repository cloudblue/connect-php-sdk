<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Product\Actions;

use Connect\ConnectClient;
use Connect\Model;

class Action extends Model
{
    /**
     * @var string
     */

    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $scope;

    /**
     * @var string
     */
    public $description;

    public function getActionLink(\Connect\Asset $asset)
    {
        if (!isset($this->id)) {
            return new PALink();
        }
        $body = ConnectClient::getInstance()->directory->sendRequest('GET', '/products/'.$asset->product->id.'/actions/'.$this->id.'/actionLink');
        return Model::modelize('PALink', json_decode($body));
    }
}
