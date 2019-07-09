<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

class ProductCustomerUISettings extends Model
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $getting_started;

    /**
     * @var ProductDownloadLink[]
     */
    public $download_links;

    /**
     * @var ProductDocument[]
     */
    public $documents;

    public function __construct($source = null)
    {
        parent::__construct($source);
        $this->download_links = Model::modelize('ProductDownloadLink', $this->download_links);
        $this->documents = Model::modelize('ProductDocument', $this->documents);
    }
}
