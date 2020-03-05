<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class FileUsageRecord
 * @package Connect
 */
class FileUsageRecord extends \Connect\Model
{
    /**
     * @var string
     * Unique identifier of the usage record
     */
    public $record_id;

    /**
     * @var string | null
     * Optional note
     */
    public $record_note;

    /**
     * @var string
     * Macro that will be used to find out respective item in product
     */
    public $item_search_criteria;

    /**
     * @var string
     * Value that will be used to identify item within product with the help of filter specified on 'item_search_criteria'
     */
    public $item_search_value;

    /**
     * @var float | null
     * Usage amount of belong to a item of an asset
     * Only needed for CR, PR and TR Schemas
     */
    public $amount;

    /**
     * @var float
     */
    public $quantity;

    /**
     * @var string
     */
    public $start_time_utc;

    /**
     * @var string
     */
    public $end_time_utc;

    /**
     * @var string
     * Macro that will be used to find out respective asset belonging to the product
     */
    public $asset_search_criteria;

    /**
     * @var string
     * Value that will be used to identify Asset belonging to the product with the help of filter specified on 'asset_search_criteria'
     */
    public $asset_search_value;

    /**
     * @var string
     * Item name to which usage records belongs to, only for reporting items that was not part of product definition
     * Items are reported and created dynamically
     */
    public $item_name;

    /**
     * @var string
     * Item MPN to which usage records belongs to, only for reporting items that was not part of product definition
     * Items are reported and created dynamically
     */
    public $item_mpn;

    /**
     * @var string
     * Only for reporting items that was not part of product definition
     * Items are reported and created dynamically
     */
    public $item_unit;

    /**
     * @var string
     * Precision of the item for which usage record belong to.
     * Input data should be one of the choices value
     * ( integer, decimal(1), decimal(2), decimal(4), decimal(8) )
     * Only for reporting items that was not part of product definition
     * Items are reported and created dynamically
     */
    public $item_precision;

    /**
     * @var string
     * Category id to link this usage record with a category.
     */
    public $category_id;

    /**
     * @var string \ null
     * Optional: Asset reconciliation ID provided by vendor. This value comes from a parameter value of the asset that is marked as recon id by vendor.
     */
    public $asset_recon_id;

    /**
     * @var int | null
     * Tier level specified for linking usage record with a tier account of Asset in case of TR schema.
     */
    public $tier;
}
