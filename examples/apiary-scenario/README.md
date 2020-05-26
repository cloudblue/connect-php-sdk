![Apiary Scenario Vendor](https://connect.cloudblue.com/community/sdk/vendor-scenario-example/fulfillment/manage-asset/asset-request-wit-php-sdk/)

# Apiary Scenario Vendor Template for PHP

This template was develop based on the Connect SDK Template functionallity. 
The Connect SDK Template for php provides developers an complete skeleton to start their automation project using the [Connect Fulfillment API](http://help.vendor.connect.cloud.im/support/solutions/articles/43000030735-fulfillment-management-module) together with the [Connect SDK for PHP](https://github.com/ingrammicro/connect-php-sdk).

## Requirements

In order to use this template you will need an environment capable to run PHP scripts, any version starting PHP 5.6 is supported. Additionally please ensure that [composer](https://getcomposer.org/) it's functional.

## Installation 

Once you has pulled the project, go to the folder examples/apiary-scenario and run "composer update" to get the latest versions of the dependencies and to update the file composer.lock

## PHP
In the config.php you must to set the API Vendor credentials. 
In `app/ProductFulfillment.php` file in the `processRequest()` method is the logic of the process.
This have two parts. 

Create tenant: Process every request that not have filled the parameter tenantId, calling the API Vendor System simulated by Apiary making a POST with the new tenant. Cactch the Id of the response and store in the parameter tenantId of the Purchase Request in Connect. 

Process tenant: Verify the status of every request into Vendor System and if the status="ready", process the Purchase Request in Connect. 
