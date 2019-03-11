<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\UsageAutomation;
use Connect\Usage\FileUsageRecord;
USE \Connect\Usage\File;

/**
 * Class UsageAutomationHelper
 * @property \stdClass $std
 * @package Test\Unit
 */
class UsageAutomationHelper extends UsageAutomation
{
    public function processUsageForListing($listing)
    {
        switch ($listing->contract->id) {
            case 'CRD-99082-45842-69181':
                $usageFile = new File([
                    'name' => 'sdk test',
                    'product' => new \Connect\Product(
                        ['id' => $listing->product->id]
                    ),
                    'contract' => new \Connect\Contract(
                        ['id' => $listing->contract->id]
                    )
                ]);
                date_default_timezone_set('UTC');
                $usages = [];
                array_push($usages, new FileUsageRecord([
                    'item_search_criteria' => 'item.mpn',
                    'item_search_value' => 'SKUA',
                    'quantity' => 1,
                    'start_time_utc' => date('d-m-Y', strtotime("-1 days")),
                    'end_time_utc' => date("Y-m-d H:i:s"),
                    'asset_search_criteria' => 'parameter.param_b',
                    'asset_search_value' => 'tenant2'
                ]));
                $this->submitUsage($usageFile, $usages);
                return "Processed";
            case 'CRD-99082-45842-69186':
                $usageFile = new File([
                    'name' => 'sdk test',
                    'product' => new \Connect\Product(
                        ['id' => $listing->product->id]
                    ),
                    'contract' => new \Connect\Contract(
                        ['id' => $listing->contract->id]
                    )
                ]);
                date_default_timezone_set('UTC');
                $usages = [];
                array_push($usages, new FileUsageRecord([
                    'item_search_criteria' => 'item.mpn',
                    'item_search_value' => 'SKUA',
                    'quantity' => 1,
                    'start_time_utc' => date('d-m-Y', strtotime("-1 days")),
                    'end_time_utc' => date("Y-m-d H:i:s"),
                    'asset_search_criteria' => 'parameter.param_b',
                    'asset_search_value' => 'tenant2'
                ]));
                $this->submitUsage($usageFile, $usages);
                return "Processed";
            case 'CRD-99082-45842-69187':
                $usageFile = new File([
                    'name' => 'sdk test',
                    'product' => new \Connect\Product(
                        ['id' => $listing->product->id]
                    ),
                    'contract' => new \Connect\Contract(
                        ['id' => $listing->contract->id]
                    )
                ]);
                date_default_timezone_set('UTC');
                $usages = [];
                array_push($usages, new FileUsageRecord([
                    'item_search_criteria' => 'item.mpn',
                    'item_search_value' => 'SKUA',
                    'quantity' => 1,
                    'start_time_utc' => date('d-m-Y', strtotime("-1 days")),
                    'end_time_utc' => date("Y-m-d H:i:s"),
                    'asset_search_criteria' => 'parameter.param_b',
                    'asset_search_value' => 'tenant2'
                ]));
                $this->submitUsage($usageFile, $usages);
                return "Processed";
            case 'CRD-99082-45842-69182':
                return true;
            case 'CRD-99082-45842-69183':
                $something = new \stdClass();
                $something->message = "Some strange object";
                return $something;
            case 'CRD-99082-45842-69184':
                $usageFile = new File([
                    'product' => new \Connect\Product(
                        ['id' => $listing->product->id]
                    ),
                    'contract' => new \Connect\Contract(
                        ['id' => $listing->contract->id]
                    )
                ]);
                date_default_timezone_set('UTC');
                $usages = [];
                array_push($usages, new FileUsageRecord([
                    'item_search_criteria' => 'item.mpn',
                    'item_search_value' => 'SKUA',
                    'quantity' => 1,
                    'start_time_utc' => date('d-m-Y', strtotime("-1 days")),
                    'end_time_utc' => date("Y-m-d H:i:s"),
                    'asset_search_criteria' => 'parameter.param_b',
                    'asset_search_value' => 'tenant2'
                ]));
                $this->submitUsage($usageFile, $usages);
            case 'CRD-99082-45842-69185':
                return "YES is an string";
            default:
                throw new \Exception("NO test going over default");
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getHttp()
    {
        return $this->http;
    }

    public function getStd()
    {
        return $this->std;
    }
}