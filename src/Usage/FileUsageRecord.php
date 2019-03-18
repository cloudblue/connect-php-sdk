<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class FileUsageRecord
 * @package Connect
 */
class FileUsageRecord extends \Connect\Model
{
    public $record_id;
    public $item_search_criteria;
    public $item_search_value;
    public $quantity;
    public $start_time_utc;
    public $end_time_utc;
    public $asset_search_criteria;
    public $asset_search_value;

}