<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Modules;

use Connect\Config;
use Connect\Constants;
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

        $body = $this->sendRequest('GET', Constants::USAGE_FILES_PATH . $query);

        /** @var File[] $models */
        $models = Model::modelize('files', json_decode($body));
        foreach ($models as $index => $model) {
            $models[$index]->requestProcessor = $this;
        }

        return $models;
    }

    /**
     * @param File $usageFile
     * @return array|File
     * @throws FileCreationException
     * @throws GuzzleException
     */
    public function createUsageFile(File $usageFile)
    {
        if (!isset($usageFile->name) || !isset($usageFile->product->id) || !isset($usageFile->contract->id)) {
            throw new FileCreationException("Usage File Creation requires name, product id, contract id");
        }

        if (!isset($usageFile->description)) {
            $usageFile->description = "";
        }

        $body = $this->sendRequest('POST', Constants::USAGE_FILES_PATH, $usageFile);
        return Model::modelize('file', json_decode($body));
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
            Constants::SPREADSHEET_SHEET_NAME
        ), 0);
        $spreadSheet->setActiveSheetIndexByName(Constants::SPREADSHEET_SHEET_NAME);
        $spreadSheet->getActiveSheet()->setCellValue('A1', "record_id");
        $spreadSheet->getActiveSheet()->setCellValue('B1', "record_note");
        $spreadSheet->getActiveSheet()->setCellValue('C1', "item_search_criteria");
        $spreadSheet->getActiveSheet()->setCellValue('D1', "item_search_value");
        $spreadSheet->getActiveSheet()->setCellValue('E1', "amount");
        $spreadSheet->getActiveSheet()->setCellValue('F1', "quantity");
        $spreadSheet->getActiveSheet()->setCellValue('G1', "start_time_utc");
        $spreadSheet->getActiveSheet()->setCellValue('H1', "end_time_utc");
        $spreadSheet->getActiveSheet()->setCellValue('I1', "asset_search_criteria");
        $spreadSheet->getActiveSheet()->setCellValue('J1', "asset_search_value");
        $spreadSheet->getActiveSheet()->setCellValue('K1', "item_name");
        $spreadSheet->getActiveSheet()->setCellValue('L1', "item_mpn");
        $spreadSheet->getActiveSheet()->setCellValue('M1', "item_precision");
        $spreadSheet->getActiveSheet()->setCellValue('N1', "category_id");
        $spreadSheet->getActiveSheet()->setCellValue('O1', "asset_recon_id");
        $spreadSheet->getActiveSheet()->setCellValue('P1', "tier");

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
                trim($this->config->apiEndpoint . Constants::USAGE_FILES_PATH . $usageFile->id . $path),
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
        $spreadSheet->setActiveSheetIndexByName(Constants::SPREADSHEET_SHEET_NAME);
        for ($i = 0; $i < count($fileusagerecords); $i++) {
            $spreadSheet->getActiveSheet()->setCellValue('A' . ($i + 2), $fileusagerecords[$i]->record_id);
            $spreadSheet->getActiveSheet()->setCellValue('B' . ($i + 2), $fileusagerecords[$i]->record_note);
            $spreadSheet->getActiveSheet()->setCellValue('C' . ($i + 2), $fileusagerecords[$i]->item_search_criteria);
            $spreadSheet->getActiveSheet()->setCellValue('D' . ($i + 2), $fileusagerecords[$i]->item_search_value);
            $spreadSheet->getActiveSheet()->setCellValue('E' . ($i + 2), (is_null($fileusagerecords[$i]->amount) ? "":$fileusagerecords[$i]->amount));
            $spreadSheet->getActiveSheet()->setCellValue('F' . ($i + 2), (is_null($fileusagerecords[$i]->quantity) ? "":$fileusagerecords[$i]->quantity));
            $spreadSheet->getActiveSheet()->setCellValue('G' . ($i + 2), $fileusagerecords[$i]->start_time_utc);
            $spreadSheet->getActiveSheet()->setCellValue('H' . ($i + 2), $fileusagerecords[$i]->end_time_utc);
            $spreadSheet->getActiveSheet()->setCellValue('I' . ($i + 2), $fileusagerecords[$i]->asset_search_criteria);
            $spreadSheet->getActiveSheet()->setCellValue('J' . ($i + 2), $fileusagerecords[$i]->asset_search_value);
            $spreadSheet->getActiveSheet()->setCellValue('K' . ($i + 2), (is_null($fileusagerecords[$i]->item_name) ? "":$fileusagerecords[$i]->item_name));
            $spreadSheet->getActiveSheet()->setCellValue('L' . ($i + 2), (is_null($fileusagerecords[$i]->item_mpn) ? "":$fileusagerecords[$i]->item_mpn));
            $spreadSheet->getActiveSheet()->setCellValue('M' . ($i + 2), (is_null($fileusagerecords[$i]->item_precision) ? "":$fileusagerecords[$i]->item_precision));
            $spreadSheet->getActiveSheet()->setCellValue('N' . ($i + 2), (is_null($fileusagerecords[$i]->category_id) ? "":$fileusagerecords[$i]->category_id));
            $spreadSheet->getActiveSheet()->setCellValue('O' . ($i + 2), (is_null($fileusagerecords[$i]->asset_recon_id) ? "":$fileusagerecords[$i]->asset_recon_id));
            $spreadSheet->getActiveSheet()->setCellValue('P' . ($i + 2), (is_null($fileusagerecords[$i]->tier) ? "":$fileusagerecords[$i]->tier));
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
     * @return array|File
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
