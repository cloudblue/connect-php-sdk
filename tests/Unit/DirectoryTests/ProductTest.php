<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\DirectoryTests;

use Connect\Asset;
use Connect\Config;
use Connect\ConnectClient;
use Connect\RQL\Query;

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
    public function testGetProductsFilter()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.ProductList.json'));
        $products = $connectClient->directory->listProducts(new Query([
            'limit' => 1
        ]));
        foreach ($products as $product) {
            $this->assertInstanceOf("\Connect\Product", $product);
        }
        $this->assertEquals(1, count($products));
    }

    public function testGetProductsFilter2()
    {
        $connectClient = new ConnectClient(new Config(__DIR__. '/config.mocked.ProductList.json'));
        $products = $connectClient->directory->listProducts([
            'limit' => 1
        ]);
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
        foreach ($templates as $template) {
            $this->assertInstanceOf('\Connect\template', $template);
        }
        $configurations = $product->getProductConfigurations();
        foreach ($configurations as $configuration) {
            $this->assertInstanceOf('\Connect\ProductConfigurationParameter', $configuration);
        }
        $mediaObject = $product->getAllMedia();
        foreach ($mediaObject as $media) {
            $this->assertInstanceOf('\Connect\ProductMedia', $media);
        }
        $items = $product->getAllItems();
        foreach ($items as $item) {
            $this->assertInstanceOf('\Connect\Item', $item);
        }
        $agreements = $product->getAllAgreements();
        foreach ($agreements as $agreement) {
            $this->assertInstanceOf('\Connect\Agreement', $agreement);
        }
        $actions = $product->getAllActions();
        foreach ($actions as $action) {
            $this->assertInstanceOf('Connect\Product\Actions\Action', $action);
        }
        $asset = new Asset(json_decode(file_get_contents(__DIR__.'/../Runtime/Providers/requestassets.json')));
        $link = $actions[0]->getActionLink($asset);
        $this->assertInstanceOf('Connect\Product\Actions\PALink', $link);
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

    public function testEmptyProductMedia()
    {
        $product = new \Connect\Product();
        $media = $product->getAllMedia();
        $this->assertCount(0, $media);
    }

    public function testEmptyItem()
    {
        $product = new \Connect\Product();
        $items = $product->getAllItems();
        $this->assertCount(0, $items);
    }

    public function testEmptyAgreements()
    {
        $product = new \Connect\Product();
        $agreements = $product->getAllAgreements();
        $this->assertCount(0, $agreements);
    }

    public function testEmptyActions()
    {
        $product = new \Connect\Product();
        $actions = $product->getAllActions();
        $this->assertCount(0, $actions);
    }

    public function testEmptyActionLink()
    {
        $action = new \Connect\Product\Actions\Action();
        $this->assertInstanceOf('\Connect\Product\Actions\PALink', $action->getActionLink(new Asset()));
    }
}
