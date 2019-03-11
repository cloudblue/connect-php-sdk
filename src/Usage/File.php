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
     * @var
     */
    public $id;

    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $description;

    /**
     * @var
     */

    public $note;

    /**
     * @var
     */
    public $created_by;

    /**
     * @var
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

    public $upload_file_uri;
    public $processed_file_uri;
    public $acceptance_note;
    public $rejection_note;
    public $error_detail;

    /**
     * @var \Connect\Usage\Records
     */
    public $records;

    public $uploaded_by;
    public $uploaded_at;
    public $submitted_by;
    public $submitted_at;
    public $accepted_by;
    public $accepted_at;
    public $rejected_by;
    public $rejected_at;
    public $closed_by;
    public $closed_at;

}