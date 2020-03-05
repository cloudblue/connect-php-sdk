<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Connect\Usage\Accept;
use Connect\Usage\Close;
use Connect\Usage\Delete;
use Connect\Usage\Reject;
use Connect\Usage\Submit;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * Class UsageFileAutomation
 * @property Config $config
 * @property LoggerInterface $logger
 * @property ClientInterface $http
 *
 * @package Connect
 */
abstract class UsageFileAutomation extends AutomationEngine implements UsageFileAutomationInterface
{
    /**
     * @param bool $all - optional, if true gets all usage files
     * @throws GuzzleException
     */
    public function process($all = false)
    {
        $filter = [];
        if ($all == false) {
            $filter = ['status' => 'ready'];
        }
        foreach ($this->usage->listUsageFiles($filter) as $usageFile) {
            $this->dispatchUsageFileProcessing($usageFile);
        }
    }

    /**
     * @param Usage\File $usageFile
     * @return string
     * @throws GuzzleException
     */
    protected function dispatchUsageFileProcessing($usageFile)
    {
        try {
            if ($this->config->products && !in_array($usageFile->product->id, $this->config->products)) {
                return 'Invalid product';
            }

            $processingResult = 'unknown';

            $this->logger->info("Starting processing of Usage File ID=" . $usageFile->id);

            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $msg = $this->processUsageFiles($usageFile);

            if (is_string($msg)) {
                $this->logger->warning("ProcessUsageFiles returned $msg while is expected to return Exception of Usage types");
            } elseif ($msg) {
                $this->logger->warning("ProcessUsageFiles returned instance of " . gettype($msg) . " while is expected to return Exception of Usage types");
            }
        } catch (Accept $e) {
            $this->usage->sendRequest(
                'POST',
                Constants::USAGE_FILES_PATH. $usageFile->id . '/accept/',
                json_encode(['acceptance_note' => $e->getMessage()])
            );
            $processingResult = 'accept';
        } catch (Close $e) {
            $this->usage->sendRequest(
                'POST',
                Constants::USAGE_FILES_PATH . $usageFile->id . '/close/',
                '{}'
            );
            $processingResult = 'close';
        } catch (Delete $e) {
            $this->usage->sendRequest(
                'POST',
                Constants::USAGE_FILES_PATH . $usageFile->id . '/delete/',
                '{}'
            );
            $processingResult = 'delete';
        } catch (Reject $e) {
            $this->usage->sendRequest(
                'POST',
                Constants::USAGE_FILES_PATH . $usageFile->id . '/reject/',
                json_encode(['rejection_note' => $e->getMessage()])
            );
            $processingResult = 'reject';
        } catch (Submit $e) {
            $this->usage->sendRequest(
                'POST',
                Constants::USAGE_FILES_PATH . $usageFile->id . '/submit/',
                '{}'
            );
            $processingResult = 'submit';
        } catch (\Connect\Usage\Skip $e) {
            $processingResult = 'skip';
        }

        $this->logger->info("Finished processing of Usage File ID=" . $usageFile->id . " result=" . $processingResult);

        return $processingResult;
    }
}
