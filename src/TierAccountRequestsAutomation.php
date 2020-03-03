<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * Class TierAccountRequestsAutomation
 * @property Config $config
 * @property LoggerInterface $logger
 * @property ClientInterface $http
 *
 * @package Connect
 */
abstract class TierAccountRequestsAutomation extends AutomationEngine implements TierAccountRequestsAutomationInterface
{

    /**
     * @param null $filter
     * @throws GuzzleException
     */
    public function process()
    {
        foreach ($this->directory->listTierAccountRequests(['status' => 'pending']) as $tierAccountRequest) {
            $this->dispatchTierAccountRequest($tierAccountRequest);
        }
    }

    /**
     * @param $tierAccountRequest
     * @return string
     * @throws GuzzleException
     */
    protected function dispatchTierAccountRequest($tierAccountRequest)
    {
        try {
            if ($this->config->products && !in_array($tierAccountRequest->product->id, $this->config->products)) {
                return 'Invalid product';
            }

            $processingResult = 'unknown';

            $this->logger->info("Starting processing of Tier Account request with ID=" . $tierAccountRequest->id);

            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $msg = $this->processTierAccountRequest($tierAccountRequest);

            if (is_string($msg)) {
                $this->logger->warning("Result of Processing Tier account request returned $msg while is expected to return Exception of Tier Account Request types (TierAccountRequestAccept or TierAccountRequesIgnore)");
            } elseif ($msg) {
                $this->logger->warning("Result of Processing Tier account request returned instance of " . gettype($msg) . "  while is expected to return Exception of Tier Account Request types (TierAccountRequestAccept or TierAccountRequesIgnore)");
            }
        } catch (TierAccountRequestAccept $e) {
            $this->directory->sendRequest(
                'POST',
                Constants::TIER_ACCOUNT_REQUESTS_PATH. $tierAccountRequest->id . '/accept/',
                '{}'
            );
            $processingResult = 'accept';
        } catch (TierAccountRequestIgnore $e) {
            $this->usage->sendRequest(
                'POST',
                Constants::TIER_ACCOUNT_REQUESTS_PATH. $tierAccountRequest->id . '/ignore/',
                json_encode(['reason' => $e->getMessage()])
            );
            $processingResult = 'ignore';
        }

        $this->logger->info("Finished processing of Tier Account Request with ID=" . $tierAccountRequest->id . " result=" . $processingResult);

        return $processingResult;
    }
}
