<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class TierAccountRequest
 * @package Connect
 */

class TierAccountRequest extends Model
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
     * @var TierAccount
     */
    public $account;

    /**
     * @var Vendor
     */
    public $vendor;

    /**
     * @var Provider
     */
    public $provider;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var string
     */
    public $reason;

    /**
     * @var Events
     */
    public $events;

    public function setAccount($account)
    {
        $this->account = Model::modelize('TierAccount', $account);
    }
}
