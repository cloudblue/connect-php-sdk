<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Connect\Usage\FileCreationException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class UsageAutomation
 * @package Connect
 */
abstract class UsageAutomation extends AutomationEngine implements UsageAutomationInterface
{

    /**
     * Process all requests
     * @throws GuzzleException
     */
    public function process()
    {
        foreach ($this->usage->listListings(['status' => 'listed']) as $listing) {
            $this->dispatchProductUsageCollection($listing);
        }
    }

    /**
     * @param Listing $listing
     * @return string
     */
    protected function dispatchProductUsageCollection($listing)
    {
        if ($this->config->products && !in_array(
                $listing->product->id,
                $this->config->products
            )) {
            return 'Listing not handled by this processor';
        };
        $this->logger->info("Processing Usage for Product " . $listing->product->id . " (" . $listing->product->name . ") on Contract " . $listing->contract->id . " and provider " . $listing->provider->id . "(" . $listing->provider->name . ")");
        /** @noinspection PhpVoidFunctionResultUsedInspection */

        try {
            $msg = $this->processUsageForListing($listing);
        } catch (FileCreationException $e) {
            $this->logger->error("Error proocessing Usage for Product " . $listing->product->id . " (" . $listing->product->name . ") on Contract " . $listing->contract->id . " and provider " . $listing->provider->id . "(" . $listing->provider->name . ")");
            $processingResult = "failure";
            return $processingResult;
        }
        if (is_string($msg)) {
            $this->logger->info("Processing result for usage on listing " . $listing->product->id . ":" . $msg);
            $processingResult = 'success';
            return $processingResult;
        } else {
            if ($msg === true) {
                $this->logger->info("Processing result for usage on listing " . $listing->product->id . ":success");
                $processingResult = 'success';
            } else {
                $this->logger->info("processUsageForListing returned object of type " . gettype($msg) . ". Expected string for success or boolean");
                $processingResult = 'unknown';
            }
        }
        return $processingResult;
    }

}
