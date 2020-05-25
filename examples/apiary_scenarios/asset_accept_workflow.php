<?php
require_once "vendor/autoload.php";

class ProcessRequests extends \Connect\AutomationEngine
{    
    /**
     * Process all requests
     * @throws GuzzleException
     * @throws ConfigException
     * 
     */
    public function process()
    {
        foreach ($this->fulfillment->listRequests(['status' => 'pending']) as $request) {   
            $this->dispatch($request);
        }
    }

    /**
     * @param Request $request
     * @return string
     * @throws GuzzleException
     * @throws ConfigException
     */
    protected function dispatch($request)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', 'https://private-368580-vendorexample.apiary-mock.com/tenant?externalId='.$request->asset->id);
        $data = json_decode($res->getBody(), True);
        if ($data['status'] == "ready"){
            $product = new \Connect\Product();
            $product->id = $request->asset->product->id;
            $templates = $product->getTemplates();
            foreach ($templates as $template) {
                if($template['type']=="fulfillment"){
                    $templateId = $template['id'];
                }
                break;
            }
            $urlStr = "/requests/".$request->id."/approve";
            $this->fulfillment->sendRequest(
                'POST',
                $urlStr,
                json_encode(['template_id' => $templateId])
            );
            $this->logger->info("The Tenant ".$request->asset->id." was processed in Connect");            
        } else {
            $this->logger->info("The Tenant ".$request->asset->id." is in process or not exist yet");
        }
    }
}

//Main Code Block

try {
    //In case Config is not passed into constructor, configuration from config.json is used
    $requests = new processRequests();
    
    $requests->process();
    
} catch (Exception $e) {
    $this->logger->info("Error processing requests:" . $e->getMessage());
}