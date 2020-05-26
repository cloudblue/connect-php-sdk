<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace App;

/**
 * Class ProductFulfillment
 * @package App
 */
define('URL_APIARY', 'https://private-368580-vendorexample.apiary-mock.com/');

 class ProductFulfillment extends \Connect\FulfillmentAutomation
{
    /**
     * Process each pending request
     * @param \Connect\Request $request
     */

    public function processRequest($request)
    {
        $client = new \GuzzleHttp\Client();
        switch($request->type){
            case 'purchase':{
                $parameterTenantId = $request->asset->getParameterById('tenantId')->value;
                if($parameterTenantId === "") {
                    if(count($request->asset->items)==1){
                        foreach ($request->asset->items as $item) {
                            $mpn = $item->mpn;
                            $quantity = $item->quantity;
                            break;
                        };
                        $body = array(
                            'Attributes' => [
                                'product' => [
                                    'item' => $mpn,
                                    'quantity' => $quantity
                                ],
                                'account' => [
                                    'accountFirstName' => $request->asset->tiers->customer->contact_info->contact->first_name,
                                    'accountLastName' => $request->asset->tiers->customer->contact_info->contact->last_name,
                                    'accountCompany' => $request->asset->tiers->customer->contact_info->address_line1,
                                    'accountAddress' => $request->asset->tiers->customer->contact_info->address_line1,
                                    'accountCity' => $request->asset->tiers->customer->contact_info->city,
                                    'accountState' => $request->asset->tiers->customer->contact_info->state,
                                    'accountPostalCode' => $request->asset->tiers->customer->contact_info->postal_code,
                                    'accountCountry' => $request->asset->tiers->customer->contact_info->country,
                                    'accountEmail' => $request->asset->tiers->customer->contact_info->contact->email,
                                    'accountPhone' => $request->asset->tiers->customer->contact_info->contact->phone_number->phone_number
                                ]
                            ]
                        );
                        $res = $client->request('POST', URL_APIARY.'tenant', [
                            'body' => json_encode($body)
                        ]);
                        $response = json_decode($res->getBody(), True);
                        $this->logger->info("Tenant:".$response['tenantId']);
                        $this->logger->info("External ID:".$response['externalId']);
                        $this->logger->info("Product Item Id:".$response['productItemId']);
                        $this->logger->info("Status:".$response['status']);
                        if($response['tenantId']){
                            $this->updateParameters($request, array (
                             new \Connect\Param([
                                    'id' => 'tenantId',
                                    'value' => $response['tenantId']
                                ])
                            )); 
                        } else {
                            $this->logger->info("Error processing tenant in Vendor System");
                        }
                    } else {
                        $this->logger->info("Request malformed, too many items");
                    }
                    throw new \Connect\Skip("Moving to next request");
                } else {
                    $res = $client->request('GET', URL_APIARY.'tenant/'.$parameterTenantId);
                    $data = json_decode($res->getBody(), True);
                    if ($data['status'] === 'ready'){
                        $this->logger->info("Service is ready on Vendor System");
                        return new \Connect\ActivationTemplateResponse($request->asset->configuration->getParameterById('templateId')->value);
                    }
                }
            }
            default:
                throw new \Connect\Skip("This processor not handle this type of request: ".$request->type);
        }
    }

    /**
    * Processing each pending Tier Config
    * @param \Connect\TierConfigRequest $tierConfigRequest
    */
    public function processTierConfigRequest($tierConfigRequest)
    {
        // TODO: Implement processTierConfigRequest() method
    }

    /**
     * Run the Product Fulfillment Request Processor
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function run()
    {
        try {

            /**
             * run the application in custom context, any error
             * handling customization should be done here
             */
            $this->process();
            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            if (is_callable([$this->logger, 'dump'])) {
                $this->logger->dump();
            }
        }

        return false;
    }

}
