<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\DirectoryTests;

use Connect\Config;
use Connect\ConnectClient;

class ProductTest extends \Test\TestCase
{
    public function testGetProducts()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.ProductList.json'));
        $products = $connectClient->directory->listProducts();
        foreach ($products as $product) {
            $this->assertInstanceOf("\Connect\Product", $product);
        }
        $this->assertEquals(1, count($products));
    }

    public function testGetProduct()
    {
        $connectClient = new ConnectClient(new Config(__DIR__ . '/config.mocked.getProduct.json'));
        $product = $connectClient->directory->getProduct('PRD-086-505-671');
        $this->assertInstanceOf("\Connect\Product", $product);
        $templates = $product->getTemplates();
        foreach($templates as $template)
        {
            $this->assertInstanceOf('\Connect\template', $template);
        }
        $configurations = $product->getProductConfigurations();
        foreach($configurations as $configuration)
        {
            $this->assertInstanceOf('\Connect\ProductConfigurationParameter', $configuration);
        }
    }

    public function testEmptyProductTemplates()
    {
        $product = new \Connect\Product();
        $templates = $product->getTemplates();
        $this->assertCount(0, $templates);
    }

    public function testEmptyProductConfigurations()
    {
        $product = new \Connect\Product();
        $configurations = $product->getProductConfigurations();
        $this->assertCount(0, $configurations);
    }

}
