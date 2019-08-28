<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Modules;

use Connect\Config;
use Connect\Listing;
use Connect\Model;
use Connect\Product;
use Connect\Usage\File;
use Connect\Usage\FileCreationException;
use Connect\Usage\FileRetrieveException;
use Connect\Usage\FileUsageRecord;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;

/**
 * Class Usage
 * @package Connect\Modules
 */
class Usage extends Core
{
    /**
     * Request Fulfillment service
     * @var Fulfillment
     */
    public $fulfillment;

    /**
     * TierConfiguration Service
     * @var TierConfiguration
     */
    public $tierConfiguration;

    /**
     * Fulfillment constructor.
     * @param Config $config
     * @param LoggerInterface $logger
     * @param ClientInterface $http
     * @param TierConfiguration $tierConfiguration
     * @param Fulfillment $fulfillment
     */
    public function __construct(Config $config, LoggerInterface $logger, ClientInterface $http, TierConfiguration $tierConfiguration, Fulfillment $fulfillment)
    {
        parent::__construct($config, $logger, $http);

        $this->tierConfiguration = $tierConfiguration;
        $this->fulfillment = $fulfillment;
    }

    /**
     * Lists the product listings
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return Listing[]
     * @throws GuzzleException
     */
    public function listListings(array $filters = [])
    {
        $query = '';

        if ($this->config->products) {
            $filters['product__id'] = $this->config->products;
        }

        if ($filters) {
            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', http_build_query($filters));
        }

        $body = $this->sendRequest('GET', '/listings' . $query);

        /** @var Listing[] $models */
        $models = Model::modelize('listings', json_decode($body));
        foreach ($models as $index => $model) {
            $models[$index]->requestProcessor = $this;
        }

        return $models;
    }

    /**
     * List the usage Files
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return File[]
     * @throws GuzzleException
     */
    public function listUsageFiles(array $filters = null)
    {
        $query = '';

        if ($this->config->products) {
            $filters['product_id'] = $this->config->products;
        }

        if ($filters) {
            $query = http_build_query($filters);

            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', $query);
        }

        $body = $this->sendRequest('GET', '/usage/files/' . $query);

        /** @var File[] $models */
        $models = Model::modelize('files', json_decode($body));
        foreach ($models as $index => $model) {
            $models[$index]->requestProcessor = $this;
        }

        return $models;
    }

    /**
     * @param File $usageFile
     * @return array|Model
     * @throws FileCreationException
     * @throws GuzzleException
     */
    public function createUsageFile(File $usageFile)
    {
        if (!isset($usageFile->name) || !isset($usageFile->product->id) || !isset($usageFile->contract->id)) {
            throw new FileCreationException("Usage File Creation requieres name, product id, contract id");
        }

        if (!isset($usageFile->description)) {
            $usageFile->description = "";
        }

        $body = $this->sendRequest('POST', '/usage/files/', $usageFile);
        $model = Model::modelize('file', json_decode($body));
        return $model;
    }

    /**
     * @return Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function createUsageSpreadSheet()
    {
        $spreadSheet = new Spreadsheet();
        $spreadSheet->addSheet(new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $spreadSheet,
            "usage_records"
        ), 0);
        $spreadSheet->setActiveSheetIndexByName('usage_records');
        $spreadSheet->getActiveSheet()->setCellValue('A1', "usage_record_id");
        $spreadSheet->getActiveSheet()->setCellValue('B1', "item_search_criteria");
        $spreadSheet->getActiveSheet()->setCellValue('C1', "item_search_value");
        $spreadSheet->getActiveSheet()->setCellValue('D1', "quantity");
        $spreadSheet->getActiveSheet()->setCellValue('E1', "start_time_utc");
        $spreadSheet->getActiveSheet()->setCellValue('F1', "end_time_utc");
        $spreadSheet->getActiveSheet()->setCellValue('G1', "asset_search_criteria");
        $spreadSheet->getActiveSheet()->setCellValue('H1', "asset_search_value");
        return $spreadSheet;
    }

    /**
     * @param File $usageFile
     * @param Spreadsheet $spreadSheet
     * @return bool
     * @throws FileCreationException
     * @throws FileRetrieveException
     */
    private function uploadSpreadSheet(File $usageFile, Spreadsheet $spreadSheet)
    {
        $writer = new Xlsx($spreadSheet);
        $headers = [
            'Authorization' => 'ApiKey ' . $this->config->apiKey,
            'Request-ID' => uniqid('api-request-'),
            'Accept' => 'application/json'
        ];

        $verb = 'POST';
        $path = '/upload/';
        try {
            ob_start();
            $writer->save('php://output');
            $fileUpload = ob_get_clean();
        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            $this->logger->error("Error Creating temp worksheet for upload:" . $e->getMessage());
            throw new FileRetrieveException("Error creating worksheet for server upload");
        }
        $this->logger->info('HTTP Request: ' . strtoupper($verb) . ' ' . $this->config->apiEndpoint . $path);
        $this->logger->debug("Request Headers:\n" . print_r($headers, true));
        $multipart = [
            [
                'name' => 'usage_file',
                'contents' => $fileUpload,
                'filename' => "usage_file.xlsx"

            ]
        ];
        try {
            $response = $this->http->request(
                $verb,
                trim($this->config->apiEndpoint . "/usage/files/" . $usageFile->id . $path),
                [
                    'multipart' => $multipart,
                    'headers' => $headers
                ]
            );
        } catch (GuzzleException $e) {
            throw new FileCreationException("Error uploading file:" . $e->getMessage());
        }
        $this->logger->info('HTTP Code: ' . $response->getStatusCode());

        if ($response->getStatusCode() !== 201) {
            $this->logger->error("Unexpected response from server, obtained " . $response->getStatusCode());
            $this->logger->error("Raw response:" . $response->getBody()->getContents());
            throw new FileCreationException("Unexpected server response, returned code: " . $response->getStatusCode());
        }
        return true;
    }

    /**
     * @param array $fileusagerecords
     * @return Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function createAndPopulateSpreadSheet($fileusagerecords)
    {
        $spreadSheet = $this->createUsageSpreadSheet();
        $spreadSheet->setActiveSheetIndexByName('usage_records');
        for ($i = 0; $i < count($fileusagerecords); $i++) {
            $spreadSheet->getActiveSheet()->setCellValue('A' . ($i + 2), $fileusagerecords[$i]->record_id);
            $spreadSheet->getActiveSheet()->setCellValue('B' . ($i + 2), $fileusagerecords[$i]->item_search_criteria);
            $spreadSheet->getActiveSheet()->setCellValue('C' . ($i + 2), $fileusagerecords[$i]->item_search_value);
            $spreadSheet->getActiveSheet()->setCellValue('D' . ($i + 2), $fileusagerecords[$i]->quantity);
            $spreadSheet->getActiveSheet()->setCellValue('E' . ($i + 2), $fileusagerecords[$i]->start_time_utc);
            $spreadSheet->getActiveSheet()->setCellValue('F' . ($i + 2), $fileusagerecords[$i]->end_time_utc);
            $spreadSheet->getActiveSheet()->setCellValue('G' . ($i + 2), $fileusagerecords[$i]->asset_search_criteria);
            $spreadSheet->getActiveSheet()->setCellValue('H' . ($i + 2), $fileusagerecords[$i]->asset_search_value);
        }
        return $spreadSheet;
    }

    /**
     * @param File $usageFile
     * @param array $fileRecords
     * @return bool
     * @throws FileCreationException
     * @throws FileRetrieveException
     */
    public function uploadUsageRecords(File $usageFile, $fileRecords)
    {
        //Using XLSX mechanism till usage records json api is available
        try {
            $spreadsheet = $this->createAndPopulateSpreadSheet($fileRecords);
            return $this->uploadSpreadSheet($usageFile, $spreadsheet);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            throw new FileCreationException("Error processing usage records: " . $e->getMessage());
        }
    }

    /**
     * @param Product $product
     * @return false|string
     * @throws FileRetrieveException
     * @throws GuzzleException
     */
    public function getUsageTemplateFile(Product $product)
    {
        $downloadLocation = $this->getUsageTemplateDownloadLocation($product->id);
        try {
            $file = $this->retrieveUsageTemplate($downloadLocation);
        } catch (\Exception $e) {
            $this->logger->error("Error Obtaining template usage file from " . $downloadLocation);
            throw new FileRetrieveException("Error Obtaining template usage file from " . $downloadLocation);
        }
        return $file;
    }

    /**
     * @param string $productId
     * @return mixed
     * @throws GuzzleException
     */
    private function getUsageTemplateDownloadLocation($productId)
    {
        $body = $this->sendRequest('GET', '/usage/products/' . $productId . '/template/');
        return json_decode($body)->template_link;
    }

    /**
     * @param string $downloadlocation
     * @return false|string
     * @throws \Exception
     */
    private function retrieveUsageTemplate($downloadlocation)
    {
        $file = @file_get_contents($downloadlocation);
        if ($file === false) {
            throw new \Exception("Error obtaining Usage Template File");
        }
        return $file;
    }

    /**
     * @param File $file
     * @param FileUsageRecord[] $usageRecords
     * @return array|Model
     * @throws FileCreationException
     * @throws FileRetrieveException
     * @throws GuzzleException
     */
    public function submitUsage(File $file, $usageRecords)
    {
        $usageFile = $this->createUsageFile($file);
        $this->uploadUsageRecords($usageFile, $usageRecords);
        return $usageFile;
    }

    /**
     * Dynamically call connect native module functions.
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $_shortcuts = ['fulfillment', 'tierConfiguration'];
        foreach ($_shortcuts as $id) {
            if (is_callable([$this->{$id}, $name])) {
                return call_user_func_array([$this->{$id}, $name], $arguments);
            }
        }
    }
}
