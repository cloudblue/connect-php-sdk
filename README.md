![Connect PHP SDK](./assets/connect-logo.png)

# Connect PHP SDK

[![Build Status](https://travis-ci.com/ingrammicro/connect-php-sdk.svg?branch=master)](https://travis-ci.com/ingrammicro/connect-php-sdk) [![Latest Stable Version](https://poser.pugx.org/apsconnect/connect-sdk/v/stable)](https://packagist.org/packages/apsconnect/connect-sdk) [![License](https://poser.pugx.org/apsconnect/connect-sdk/license)](https://packagist.org/packages/apsconnect/connect-sdk) [![codecov](https://codecov.io/gh/ingrammicro/connect-php-sdk/branch/master/graph/badge.svg)](https://codecov.io/gh/ingrammicro/connect-php-sdk)
[![PHP Version](https://img.shields.io/packagist/php-v/apsconnect/connect-sdk.svg?style=flat&branch=master)](https://packagist.org/packages/apsconnect/connect-sdk)

## Getting Started
Connect PHP SDK allows an easy and fast integration with [Connect](http://connect.cloud.im/)  Fulfillment and Usage APIs with PHP-based integrations. This SDK enables you to automate fulfillment of orders for your products and report usage data for them.

Before using the library, please first to go through the documentation in the Connect knowledge base, which could be used as a source of information on the rest APIs used by this SDK.

## Class Features

This library can be utilized in your project for the automation of the fulfillment logic as well as  usage reporting. This class, once imported into your project, will enable you to:

- Establish connectivity to Connect APIs
- List all requests and apply filters like
    -	Filter requests by Product
    -	Filter requests by Status
    -	Filter requests by Asset
    -	etc.
- Process each request and obtain full details of the request
- Modify parameters of a request in order to:
    - Inquire for changes
    - Store information into the fulfillment request
- Change the status of the requests from it's initial pending state to either inquiring, failed or approved.
- Generate and upload usage files to report usage for active contracts and listings
- Process usage file status changes
- Work with Notes for requests
- Generate logs
- Collect debug logs in case of failure

Your code may use any scheduler to execute, from a simple cron to a cloud scheduler like the ones available in Azure, Google, Amazon or other cloud platforms.

## Installation & loading
Connect PHP SDK is available through [Packagist](https://packagist.org/packages/apsconnect/connect-sdk) (using semantic versioning), and installation via the [Composer](https://getcomposer.org) is the recommended way to install the Connect PHP SDK. Just add these lines to your `composer.json` file:

```json
{
  "require": {
    "apsconnect/connect-sdk": "^17.0"
    }
}
```

or run

```sh
composer require apsconnect/connect-sdk --no-dev --prefer-dist --classmap-authoritative
```

Note that the `vendor` folder and the `vendor/autoload.php` script are generated by Composer

## A Simple Example of the fulfillment

This example demonstrates a script that will retrieve all requests in the status pending and process them based on their type (purchase, change, cancel, suspend or resume)

```php
<?php

require_once "vendor/autoload.php";

class ProductRequests extends \Connect\FulfillmentAutomation
{
    
    public function processRequest($request)
    {
        $this->logger->info("Processing Request: " . $request->id . " for asset: " . $request->asset->id);
        switch ($request->type) {
            case "purchase":
                //Get value of a parameter with id "email"
                $email = $request->asset->getParameterByID('email');
                if($email->value == ""){
                    throw new \Connect\Inquire(array(
                        $request->asset->params['email']->error("Email address has not been provided, please provide one")
                    ));
                }
                //Get value for a concrete item using MPN
                $itemX = $request->asset->getItemByMPN('itemX');
                foreach ($request->asset->items as $item) {
                    if ($item->quantity > 1000000) {
                        $this->logger->info("Is Not possible to purchase product " . $item->id . " more than 1000000 time, requested: " . $item->quantity);
                        throw new \Connect\Fail("Is Not possible to purchase product " . $item->id . " more than 1000000 time, requested: " . $item->quantity);
                    }
                    else {
                        //Do some provisioning operation
                        //Update the parameters to store data
                        $paramsUpdate[] = new \Connect\Param('ActivationKey', 'somevalue');
                        $request->requestProcessor->fulfillment->updateParameters($request, $paramsUpdate);
                        //Potential actions to be done with a request:
                        // Set a parameter that requires changes and move request to inquire
                        if($requiresChanges){
                            throw new \Connect\Inquire([
                                new \Connect\Param([
                                     "id" => "email",
                                     "value_error" => "Invalid email"
                                ])
                             ]);
                        }
                        //Fail request
                        throw new \Connect\Fail("Request Can't be processed");
                        //Approve a template
                        //We may use a template defined on vendor portal as activation response, this will be what customer sees on panel
                        return new \Connect\ActivationTemplateResponse("TL-497-535-242");
                        // We may use arbitrary output to be returned as approval, this will be seen on customer panel. Please see that output must be in markup format
                        return new \Connect\ActivationTileResponse('\n# Welcome to Fallball!\n\nYes, you decided to have an account in our amazing service!\n\n');
                        // If we return empty, is approved with default message
                        return;
                    }
                }
            case "cancel":
                //Handle cancellation request
            case "change":
                //Handle change request
                //get added items:
                $newItems = $request->getNewItems();
                // get removed items:
                $removed = $request->getRemovedItems();
                // Get changed items, in other words the ones that quantity has been modified
                $changed = $request->getChangedItems();
            default:
                throw new \Connect\Fail("Operation not supported:".$request->type);
        }
    }

    public function processTierConfigRequest($tierConfigRequest){
        //This method allows processing Tier Requests, in same manner as simple requests.
        // Is required to be implemented since v15
    }
}

//Main Code Block

try {
    //In case Config is not passed into constructor, configuration from config.json is used
    $requests = new ProductRequests(new \Connect\Config([
        'apiKey' => 'Key_Available_in_ui',
        'apiEndpoint' => 'https://api.connect.cloud.im/public/v1',
        'products' => 'CN-631-322-641' #Optional value
    ]));
    
    $requests->process();
    
} catch (Exception $e) {
    
    print "Error processing requests:" . $e->getMessage();
}
```

## A Simple Example of reporting Usage Files

```php
<?php

require_once "vendor/autoload.php";

class UploadUsage extends \Connect\UsageAutomation
{

    public function processUsageForListing($listing)
    {
        //Detect concrete Provider Contract
        if($listing->contract->id === 'CRD-41560-05399-123') {
            //This is for Provider XYZ, also can be seen from $listing->provider->id and parametrized further via marketplace available at $listing->marketplace->id
            date_default_timezone_set('UTC'); //reporting must be always based on UTC
            $usages = [];
            array_push($usages, new Connect\Usage\FileUsageRecord([
                'record_id' => 'unique record value',
                'item_search_criteria' => 'item.mpn', //Possible values are item.mpn or item.local_id
                'item_search_value' => 'SKUA', //Value defined as MPN on vendor portal
                'quantity' => 1, //Quantity to be reported
                'start_time_utc' => date('d-m-Y H:i:s', strtotime("-1 days")), //From when to report
                'end_time_utc' => date("Y-m-d H:i:s"), //Till when to report
                'asset_search_criteria' => 'parameter.param_b', //How to find the asset on Connect, typical use case is to use a parameter provided by vendor, in this case called param_b, additionally can be used asset.id in case you want to use Connect identifiers
                'asset_search_value' => 'tenant2'
            ]));
            $usageFile = new \Connect\Usage\File([
                'name' => 'sdk test',
                'product' => new \Connect\Product(
                    ['id' => $listing->product->id]
                ),
                'contract' => new \Connect\Contract(
                    ['id' => $listing->contract->id]
                )
            ]);
            $this->usage->submitUsage($usageFile, $usages);
            return "processing done"
        }
        else{
            //Do Something different
        }
    }

}

//Main Code Block

try {
    
    $usageAutomation = new UploadUsage();
    $usageAutomation->process();

} catch (Exception $e) {
    print "Error processing usage for active listing requests:" . $e->getMessage();
}
```

## A Simple Example of automating workflow of Usage Files

```php
<?php

require_once "vendor/autoload.php";

class UsageFilesWorkflow extends \Connect\UsageFileAutomation
{
    
    public function processUsageFiles($usageFile)
    {
        switch ($usageFile->status){
            case 'invalid':
                //vendor and provider may handle invalid cases different, probably notifying their staff
                throw new \Connect\Usage\Delete("Not needed anymore");
                break;
            case 'ready':
                //Vendor may move to file to provider
                throw new \Connect\Usage\Submit("Ready for Provider");
            case 'pending':
                //Provider use case, needs to be reviewed and accept it
                throw new \Connect\Usage\Accept("File looks good");
            default:
                throw new \Connect\Usage\Skip("not valid status");
        }
    }
}

//Main Code Block

try {
    
    $usageWorkflow = new UsageFilesWorkflow();
    
    // is possible to ask to process all via parsing true, only applicable for
    // providers who automates own products
    $usageWorkflow->process(); 

} catch (Exception $e) {
    print "Error processing usage for active listing requests:" . $e->getMessage();
}
```

## Client class

Starting with the Connect PHP SDK version 17 the Client class has been introduced. This class allows running multiple operations in Connect like get the list of requests, configurations, etc. Client class may be instantiated from any application to obtain information needed to run an operation, like, for example, get the Asset information in the context of an action. Client will provide access to:
* Directory
* Fulfillment
* Tier Configurations

### Creating a Client

This is an example to create a client:

```php
<?php
require_once ("./vendor/autoload.php");

##Note that in case of no Configuration passed to constructor, system will check if config.json exists
$connect = new Connect\ConnectClient(new \Connect\Config([
    "apiKey" => "SU-677-956-738:ca95348138a3c122943ba968a9b69e42d30bde6c",
       "apiEndpoint" =>  "https://api.cnct.info/public/v1",
       "logLevel"=> 7,
       "timeout" =>  120,
       "sslVerifyHost"=> false
]));
$connect->directory->listTierConfigs()
```

### Connect Client usage examples:

* Retrieve  Tier Configurations
```php
<?php
$connect = new Connect\ConnectClient();
$tierConfigurations = $connect->directory->listTierConfigs();
$tierConfigurations = $connect->directory->listTierConfigs(["account.id" => 'T-0-123123132123123']);
```

* Retrieve Tier Configuration
```php
<?php
$connect = new Connect\ConnectClient();
$tierConfiguration = $connect->directory->getTierConfigById('TC-000-000-000');
```

* Retrieve list of Assets
```php
<?php
$connect = new Connect\ConnectClient();
$assets = $connect->directory->listAssets();
$assets = $connect->directory->listAssets(["product.id" => "PRD-XXXX-XXXX-XXXX"]);
```

* Retrieve an Asset
```php
<?php
$connect = new Connect\ConnectClient();
$asset = $connect->directory->getAssetById('AS-123-123-123');
```

* Get Products Information
```php
<?php
$connect = new Connect\ConnectClient();
$products = $connect->directory->listProducts();
```

* Get Product Information
```php
<?php
$connect = new Connect\ConnectClient();
$product = $connect->directory->getProduct('PRD-XXXX-XXXX-XXXX');
```

* List all requests

In case of no filter, pending ones are returned
```php
<?php
$connect = new Connect\ConnectClient();
$requests = $connect->fulfillment->listRequests();
$requests = $connect->fulfillment->listRequests(['status' => 'approved']);
```

* Get Concrete request

```php
<?php
$connect = new Connect\ConnectClient();
$request = $connect->fulfillment->getRequest('PR-XXXX-XXXX-XXXXX');
```