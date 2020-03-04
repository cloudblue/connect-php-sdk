<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

use Connect\Events;

/**
 * Class File
 * @package Connect
 */
class File extends \Connect\Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string | null
     */
    public $description;

    /**
     * @var string | null
     */

    public $note;

    /**
    * @var string
    */
    public $status;

    /**
     * @var Period
     */
    public $period;

    /**
     * @var string | null
     */

    public $currency;

    /**
     * @var string
     */

    public $schema;

    /**
     * @var \Connect\Product
     */
    public $product;

    /**
     * @var \Connect\Contract
     */
    public $contract;

    /**
     * @var \Connect\Marketplace
     */
    public $marketplace;

    /**
     * @var \Connect\Vendor
     */
    public $vendor;

    /**
     * @var \Connect\Provider
     */
    public $provider;

    /**
     * @var string | null
     */
    public $usage_file_uri;

    /**
     * @var string | null
     */
    public $processed_file_uri;

    /**
     * @var string | null
     */
    public $acceptance_note;
    /**
     * @var string | null
     */
    public $rejection_note;
    /**
     * @var string | null
     */
    public $error_detail;

    /**
     * @var string | null
     */
    public $external_id;

    /**
     * @var Stats
     */
    public $stats;

    /**
     * @var \Connect\Usage\Records
     */
    public $records;

    /**
     * @var Events
     */
    public $events;

    /**
     * @var string
     */
    public $environment;
}
