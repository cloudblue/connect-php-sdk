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
    protected $download_links;

    /**
     * @var ProductDocument[]
     */
    protected $documents;

    public function setDownload_links($downloadLinks)
    {
        $this->download_links = Model::modelize('ProductDownloadLink', $downloadLinks);
    }

    public function setDocuments($documents)
    {
        $this->documents = Model::modelize('ProductDocument', $documents);
    }
}
