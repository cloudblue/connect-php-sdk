<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Config;
use Connect\Product;

class UsageAutomationBasicsTest extends \Test\TestCase
{

    protected function setUp()
    {
        /**
         * change the work dir, by default the default config file
         * must be in the same directory of the entry point.
         */
        chdir(__DIR__);
    }

    /**
     * @return false|string
     * @throws \Connect\ConfigException
     * @throws \Connect\Usage\FileRetrieveException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @expectedException Connect\Usage\FileRetrieveException
     */
    public function testGetUsageTemplateFile()
    {
        $app = new UsageAutomationBasicsHelper(new Config('./config.mocked4usageautomation.json'));
        /* Must be removed the get_file_contents in order to complete properly the test */
        $template = $app->getUsageTemplateFile(new Product(["id" => 'PRD-638-321-603']));
        
        return $template;
    }
}