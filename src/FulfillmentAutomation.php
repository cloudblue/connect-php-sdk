<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Class FulfillmentAutomation
 * @package Connect
 */
abstract class FulfillmentAutomation extends AutomationEngine implements FulfillmentAutomationInterface
{

    /**
     * Process all requests
     * @throws GuzzleException
     * @throws ConfigException
     */
    public function process()
    {
        foreach ($this->tierConfiguration->listTierConfigs(['status' => 'pending']) as $tierConfig) {
            $this->dispatchTierConfig($tierConfig);
        }

        foreach ($this->fulfillment->listRequests(['status' => 'pending']) as $request) {
            $this->dispatch($request);
        }
    }

    /**
     * @param TierConfigRequest $tierConfigRequest
     * @return string
     * @throws GuzzleException
     */
    protected function dispatchTierConfig($tierConfigRequest)
    {
        try {
            if ($this->config->products && !in_array(
                $tierConfigRequest->configuration->product->id,
                $this->config->products
            )) {
                return 'Invalid product';
            }

            $this->logger->info("Starting processing Tier Config ID=" . $tierConfigRequest->id);

            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $msg = $this->processTierConfigRequest($tierConfigRequest);
            if (!$msg || is_string($msg)) {
                $msg = new ActivationTileResponse($msg);
            }

            if ($msg instanceof ActivationTemplateResponse) {
                $this->tierConfiguration->sendRequest(
                    'POST',
                    Constants::TIER_CONFIG_REQUESTS_PATH . $tierConfigRequest->id . Constants::APPROVE_SUFFIX,
                    json_encode(['template' => ['id' => $msg->templateid ]])
                );
                $processingResult = 'succeed (Activated using template ' . $msg->templateid . ')';
            } else {
                $this->tierConfiguration->sendRequest(
                    'POST',
                    Constants::TIER_CONFIG_REQUESTS_PATH . $tierConfigRequest->id . Constants::APPROVE_SUFFIX,
                    json_encode(['template' => ['representation' => $msg->activationTile ]])
                );
                $processingResult = 'succeed (' . $msg->activationTile . ')';
            }
        } catch (Inquire $e) {
            // update parameters and move to inquire
            $this->tierConfiguration->updateTierConfigRequestParameters($tierConfigRequest, $e->params);
            $this->tierConfiguration->sendRequest(
                'POST',
                Constants::TIER_CONFIG_REQUESTS_PATH . $tierConfigRequest->id . Constants::INQUIRE_SUFFIX,
                '{}'
            );
            $processingResult = 'inquire';
        } catch (Fail $e) {
            // fail request
            $this->tierConfiguration->sendRequest(
                'POST',
                Constants::TIER_CONFIG_REQUESTS_PATH . $tierConfigRequest->id . Constants::FAIL_SUFFIX,
                json_encode(['reason' => $e->getMessage()])
            );
            $processingResult = 'fail';
        } catch (Skip $e) {
            $processingResult = 'skip';
        }

        $this->logger->info("Finished processing of Tier Config Request with ID=" . $tierConfigRequest->id . " result=" . $processingResult);

        return $processingResult;
    }

    /**
     * @param Request $request
     * @return string
     * @throws GuzzleException
     * @throws ConfigException
     */
    protected function dispatch($request)
    {
        try {
            if ($this->config->products && !in_array($request->asset->product->id, $this->config->products)) {
                return 'Invalid product';
            }

            $this->logger->info("Starting processing of request ID=" . $request->id);

            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $msg = $this->processRequest($request);
            if (!$msg || is_string($msg)) {
                $msg = new ActivationTileResponse($msg);
            }

            if ($msg instanceof ActivationTemplateResponse) {
                $this->fulfillment->sendRequest(
                    'POST',
                    Constants::REQUESTS_PATH . $request->id . Constants::APPROVE_SUFFIX,
                    json_encode(['template_id' => $msg->templateid])
                );
                try {
                    $request->conversation()->addMessage('Activated using template ' . $msg->templateid);
                } catch (\Exception $e) {
                    $this->logger->error(Constants::GENERIC_CONVERSATION_ERROR_MESSAGE . $request->id);
                }
                $processingResult = 'succeed (Activated using template ' . $msg->templateid . ')';
            } else {
                $this->fulfillment->sendRequest(
                    'POST',
                    Constants::REQUESTS_PATH . $request->id . Constants::APPROVE_SUFFIX,
                    json_encode(['activation_tile' => $msg->activationTile])
                );
                try {
                    $request->conversation()->addMessage('Activated using Custom ActivationTile');
                } catch (GuzzleException $e) {
                    $this->logger->error(Constants::GENERIC_CONVERSATION_ERROR_MESSAGE . $request->id);
                }
                $processingResult = 'succeed (' . $msg->activationTile . ')';
            }
        } catch (Inquire $e) {
            // update parameters and move to inquire
            $this->fulfillment->updateParameters($request, $e->params);
            $this->fulfillment->sendRequest('POST', Constants::REQUESTS_PATH . $request->id . Constants::INQUIRE_SUFFIX, ($e->templateId != null) ? json_encode(['template_id' => $msg->templateId]) : '{}');
            try {
                $request->conversation()->addMessage($e->getMessage());
            } catch (GuzzleException $e) {
                $this->logger->error(Constants::GENERIC_CONVERSATION_ERROR_MESSAGE . $request->id);
            }
            $processingResult = 'inquire';
        } catch (Fail $e) {
            // fail request
            $this->fulfillment->sendRequest(
                'POST',
                Constants::REQUESTS_PATH . $request->id . Constants::FAIL_SUFFIX,
                json_encode(['reason' => $e->getMessage()])
            );
            try {
                $request->conversation()->addMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->logger->error(Constants::GENERIC_CONVERSATION_ERROR_MESSAGE . $request->id);
            }
            $processingResult = 'fail';
        } catch (Skip $e) {
            try {
                $request->conversation()->addMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->logger->error(Constants::GENERIC_CONVERSATION_ERROR_MESSAGE . $request->id);
            }
            $processingResult = 'skip';
        }

        $this->logger->info("Finished processing of request ID=" . $request->id . " result=" . $processingResult);

        return $processingResult;
    }
}
