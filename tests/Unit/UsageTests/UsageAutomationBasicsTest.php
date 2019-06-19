<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\Config;
use Connect\Product;
require_once __DIR__."/UsageAutomationBasicsHelper.php";

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
     * @expectedException \Connect\Usage\FileRetrieveException
     */
    public function testGetUsageTemplateFile()
    {
        $app = new UsageAutomationBasicsHelper(new Config('./config.mocked4usageautomationbasics.json'));
        /* Must be removed the get_file_contents in order to complete properly the test */
        $template = $app->usage->getUsageTemplateFile(new Product(["id" => 'PRD-638-321-603']));
        $app->usage->getUsageTemplateFile(new Product(["id" => 'PRD-638-321-609']));
        $app->usage->getUsageTemplateFile(new Product(['id' => 'wrongopenfile']));
        return $template;
    }

    public function testFakeShurtcut()
    {
        //to be deprecated
        $app = new UsageAutomationBasicsHelper(new Config('./config.mocked4usageautomationbasics.json'));
        $app->usage->__call("wrong", array());
    }
}
