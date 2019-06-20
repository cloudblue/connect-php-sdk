<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

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
     * @var string
     */
    public $created_by;

    /**
     * @var string
     */
    public $created_at;

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
    public $upload_file_uri;

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
     * @var \Connect\Usage\Records
     */
    public $records;

    /**
     * @var string | null
     */
    public $uploaded_by;
    /**
     * @var string | null
     */
    public $uploaded_at;
    /**
     * @var string | null
     */
    public $submitted_by;
    /**
     * @var string | null
     */
    public $submitted_at;
    /**
     * @var string | null
     */
    public $accepted_by;
    /**
     * @var string | null
     */
    public $accepted_at;
    /**
     * @var string | null
     */
    public $rejected_by;
    /**
     * @var string | null
     */
    public $rejected_at;
    /**
     * @var string | null
     */
    public $closed_by;
    /**
     * @var string | null
     */
    public $closed_at;
}
