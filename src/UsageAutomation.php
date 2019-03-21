<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

use Connect\Usage\FileUsageRecord;
use GuzzleHttp\ClientInterface;
use Pimple\Container;
use Pimple\Psr11\Container as PSRContainer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class UsageAutomation
 * @property Config $config
 * @property LoggerInterface $logger
 * @property ClientInterface $http
 *
 * @package Connect
 */
abstract class UsageAutomation implements UsageAutomationInterface
{
    /**
     * Internal Dependency Container
     * @var ContainerInterface
     */
    private $_container;

    /**
     * FulfillmentAutomation constructor.
     * @param Config|null $configuration
     * @param Container $container
     * @throws ConfigException
     */
    public function __construct(Config $configuration = null, Container $container = null)
    {
        if (!isset($configuration)) {
            $configuration = new Config('./config.json');
        }

        if (!isset($container)) {
            $container = new Container();
        }

        $container['config'] = $configuration;

        foreach ($configuration->runtimeServices as $id => $serviceProvider) {
            if (!isset($container[$id]) && class_exists($serviceProvider, true)) {
                $container[$id] = new $serviceProvider();
            }
        }

        $this->_container = new PSRContainer($container);
    }

    /**
     * Provide an access to the common libraries of the controller
     * @param string $id
     * @return object
     */
    public function __get($id)
    {
        return ($this->_container->has($id))
            ? $this->_container->get($id)
            : null;
    }

    /**
     * Send the actual request to the connect endpoint
     * @param string $verb
     * @param string $path
     * @param null|Model|string $body
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendRequest($verb, $path, $body = null)
    {
        if ($body instanceof Model) {
            $body = $body->toJSON(true);
        }

        $headers = [
            'Authorization' => 'ApiKey ' . $this->config->apiKey,
            'Request-ID' => uniqid('api-request-'),
            'Content-Type' => 'application/json',
        ];

        $this->logger->info('HTTP Request: ' . strtoupper($verb) . ' ' . $this->config->apiEndpoint . $path);
        $this->logger->debug("Request Headers:\n" . print_r($headers, true));

        if (isset($body)) {
            $this->logger->debug("Request Body:\n" . $body);
        }

        $response = $this->http->request(strtoupper($verb), trim($this->config->apiEndpoint . $path), [
            'body' => $body,
            'headers' => $headers
        ]);

        $this->logger->info('HTTP Code: ' . $response->getStatusCode());

        return $response->getBody()->getContents();
    }

    /**
     * Process all requests
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function process()
    {
        foreach ($this->listListings(['status' => 'listed']) as $listing) {
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
        } catch (Usage\FileCreationException $e) {
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

    /**
     * List the pending requests
     * @param array $filters Filter for listing key->value or key->array(value1, value2)
     * @return array|Listing
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listListings(array $filters = null)
    {
        $query = '';

        if ($this->config->products) {
            $filters['product__id'] = $this->config->products;
        }

        if ($filters) {
            $query = http_build_query($filters);

            // process case when value for filter is array
            $query = '?' . preg_replace('/%5B[0-9]+%5D/simU', '', $query);
        }

        $body = $this->sendRequest('GET', '/listings' . $query);

        /** @var Request[] $models */
        $models = Model::modelize('listings', json_decode($body));
        foreach ($models as $index => $model) {
            $models[$index]->requestProcessor = $this;
        }

        return $models;
    }

    /**
     * @param Usage\File $usageFile
     * @return array|Model
     * @throws Usage\FileCreationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createUsageFile(Usage\File $usageFile)
    {
        if (!isset($usageFile->name) || !isset($usageFile->product->id) || !isset($usageFile->contract->id)) {
            throw new Usage\FileCreationException("Usage File Creation requieres name, product id, contract id");
        }
        if (!isset($usageFile->description)) {
            $usageFile->description = "";
        }
        $body = $this->sendRequest(
            'POST',
            '/usage/files/',
            $usageFile
        );
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
     * @param Usage\File $usageFile
     * @param Spreadsheet $spreadSheet
     * @return bool
     * @throws Usage\FileCreationException
     * @throws Usage\FileRetrieveException
     */
    private function uploadSpreadSheet(
        Usage\File $usageFile,
        Spreadsheet $spreadSheet
    ) {
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
            throw new Usage\FileRetrieveException("Error creating worksheet for server upload");
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
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw new Usage\FileCreationException("Error uploading file:" . $e->getMessage());
        }
        $this->logger->info('HTTP Code: ' . $response->getStatusCode());

        if ($response->getStatusCode() !== 201) {
            $this->logger->error("Unexpected response from server, obtained " . $response->getStatusCode());
            $this->logger->error("Raw response:" . $response->getBody()->getContents());
            throw new Usage\FileCreationException("Unexpected server response, returned code: " . $response->getStatusCode());
        }
        return true;
    }


    /**
     * @param $fileusagerecords
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
     * @param Usage\File $usageFile
     * @param $fileRecords
     * @return bool
     * @throws Usage\FileCreationException
     * @throws Usage\FileRetrieveException
     */
    public function uploadUsageRecords(Usage\File $usageFile, $fileRecords)
    {
        //Using XLSX mechanism till usage records json api is available
        try {
            $spreadsheet = $this->createAndPopulateSpreadSheet($fileRecords);
            return $this->uploadSpreadSheet($usageFile, $spreadsheet);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            throw new Usage\FileCreationException("Error processing usage records: " . $e->getMessage());
        }
    }

    /**
     * @param Product $product
     * @return false|string
     * @throws Usage\FileRetrieveException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUsageTemplateFile(Product $product)
    {
        $downloadLocation = $this->getUsageTemplateDownloadLocation($product->id);
        try {
            $file = $this->retriveUsageTemplate($downloadLocation);
        } catch (\Exception $e) {
            $this->logger->error("Error Obtaining template usage file from " . $downloadLocation);
            throw new Usage\FileRetrieveException("Error Obtaining template usage file from " . $downloadLocation);
        }
        return $file;
    }

    /**
     * @param $productId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getUsageTemplateDownloadLocation($productId)
    {
        $body = $this->sendRequest(
            'GET',
            '/usage/products/' . $productId . '/template/'
        );
        return json_decode($body)->template_link;
    }

    /**
     * @param $downloadlocation
     * @return false|string
     * @throws \Exception
     */
    private function retriveUsageTemplate($downloadlocation)
    {
        $file = @file_get_contents($downloadlocation);
        if ($file === false) {
            throw new \Exception("Error obtaining Usage Template File");
        }
        return $file;
    }

    /**
     * @param Usage\File $file
     * @param FileUsageRecord[] $usageRecords
     * @return array|Model
     * @throws Usage\FileCreationException
     * @throws Usage\FileRetrieveException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submitUsage(\Connect\Usage\File $file, $usageRecords)
    {
        $usageFile = $this->createUsageFile($file);
        $this->uploadUsageRecords($usageFile, $usageRecords);
        return $usageFile;
    }
}
