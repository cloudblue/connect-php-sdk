<?php
require_once "vendor/autoload.php";

class AssetRequests extends \Connect\AutomationEngine
{
    
    /**
     * Process all requests
     * @throws GuzzleException
     * @throws ConfigException
     */
    public function process()
    {
        foreach ($this->fulfillment->listRequests(['status' => 'pending']) as $request) {   
            $this->dispatch($request);
        }
    }

    /**
     * Process each request
     * @param Request $request
     * @return string
     * @throws GuzzleException
     * @throws ConfigException
     */
    protected function dispatch($request)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', 'https://private-368580-vendorexample.apiary-mock.com/tenant?externalId=$request->asset->id');
        $data = json_decode($res->getBody(), True);
        if ($request->asset->id != $data['externalId']){
            foreach ($request->asset->items as $item) {
                $mpn = $item->mpn;
                $quantity = $item->quantity;
            break;
            };
            $body = "{
                'Attributes': {
                    'product': {
                        'item': ".$mpn.",
                        'quantity': ".$quantity."
                    },
                    'account': {
                        'accountFirstName': ".$request->asset->tiers->customer->contact_info->contact->first_name.",
                        'accountLastName': ".$request->asset->tiers->customer->contact_info->contact->last_name.",
                        'accountCompany': ".$request->asset->tiers->customer->contact_info->address_line1.",
                        'accountAddress': ".$request->asset->tiers->customer->contact_info->address_line1.",
                        'accountCity': ".$request->asset->tiers->customer->contact_info->city.",
                        'accountState': ".$request->asset->tiers->customer->contact_info->state.",
                        'accountPostalCode': ".$request->asset->tiers->customer->contact_info->postal_code.",
                        'accountCountry': ".$request->asset->tiers->customer->contact_info->country.",
                        'accountEmail': ".$request->asset->tiers->customer->contact_info->contact->email.",
                        'accountPhone': ".$request->asset->tiers->customer->contact_info->contact->phone_number->phone_number."
                    }
                }";
                $res = $client->request('POST', 'https://private-368580-vendorexample.apiary-mock.com/tenant', [
                    'body' => $body
                ]);
                $response = json_decode($res->getBody(), True);
                $this->logger->info("Tenant:".$response['tenantId']);
                $this->logger->info("External ID:".$response['externalId']);
                $this->logger->info("Product Item Id:".$response['productItemId']);
                $this->logger->info("Status:".$response['status']);
            } else {
            $this->logger->info("The Tenant ".$request->asset->id." exist in vendor");
        }
    }
}

//Main Code Block

try {
    //In case Config is not passed into constructor, configuration from config.json is used
    $requests = new AssetRequests();
    
    $requests->process();
    
} catch (Exception $e) {
    $this->logger->info("Error processing requests:" . $e->getMessage());
}