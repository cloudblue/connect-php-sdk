<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Connect;


class ProductStats extends Model
{
    /**
     * @var ProductStatsCounters
     */

    protected $contracts;

    /**
     * @var int
     */

    public $listings;

    /**
     * @var ProductStatsCounters
     */

    protected $agreements;

    public function setContracts($contracts)
    {
        $this->contracts = Model::modelize('ProductStatsCounters', $contracts);
    }

    public function setAgreements($agreements)
    {
        $this->agreements = Model::modelize('ProductStatsCounters', $agreements);
    }

}