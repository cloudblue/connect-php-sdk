# Connect PHP fulfillment SDK Change Log

## 1.1.1
    - Fixed issue with array access
    - Introduced new methods: getItemByID and getParamByID for easy access
    
## 1.1.0
    - Added autotesting coverage
    - Introduced new FulfillmentAutomation interface instead of former RequestsProcessor
    - Removed php Reflection as performance improvement
    - New Model system
    - Added Guzzle as defult http client
## 1.0.4
    - Deprecation of var for public on accessible variables to be compilant with latest standards
    - moved to psr-4 instead of psr-1
## 1.0.3
    - Fixed issue with composer autoload
## 1.0.2
    - Added filter on list-requests to improve speed when concrete product id is passed in config file
## 1.0.1
    Support for APS Connect release 12
        - New method renderTemplate to get activation template populated for a given request
        - Approve mechanism for requests supports both, activation_tile and template_id.
          in case that message returned by script matches template_id type, this one is used automatically

## 1.0.0
    Initial version of APS Connect SDK with basic features:
        - query requests list
        - invoke user-defined processing procedure
        - handle processing results
        - update request parameters if required
        - logging functionality
        - collect debug logs in case of failure
