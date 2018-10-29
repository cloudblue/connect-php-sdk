<?php

namespace Test\Unit;

use Test\TestCase;

/**
 * Class VaultConfigTest
 * @copyright Copyright (C) 2018 Ingram Micro Inc. Any rights not granted herein
 * are reserved for Ingram Micro Inc. Permission to use, copy and distribute this
 * source code without fee and without a signed license agreement is hereby granted
 * provided that: (i) the above copyright notice and this paragraph appear in all
 * copies and distributions; and (ii) the source code is only used, copied or
 * distributed for the purpose of using it with the APS package for which Ingram Micro Inc.
 * or its affiliates integrated it into. Ingram Micro Inc. may revoke the limited license
 * granted herein at any time at its sole discretion. THIS SOURCE CODE IS PROVIDED
 * "AS IS". INGRAM MICRO INC. MAKES NO REPRESENTATIONS OR WARRANTIES AND DISCLAIMS
 * ALL IMPLIED WARRANTIES OF MERCHANTABILITY OR FITNESS FOR ANY PARTICULAR PURPOSE.
 * @package Test\Unit
 */
class VaultConfigTest extends TestCase
{

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testInstantiation()
    {
        $client = $this->getMockedHttpClient(__DIR__ . '/ok.http.json', 200);

        $vault = new \Connect\Config\VaultConfig([
            'token' => 'some-token',
            'uri' => 'http://some-url/',
            'path' => '/for/vault/hash'
        ], $client);

        $this->assertInstanceOf('\Connect\Config\VaultConfig', $vault);

        $this->assertEquals('some-token', $vault->token);
        $this->assertEquals('http://some-url', $vault->uri);
        $this->assertEquals('/for/vault/hash', $vault->path);

        $this->assertEquals('ApiKey SOME-KEY-0123456789abcdef', $vault->apiKey);
        $this->assertEquals('http://api.connect.endpoint', $vault->apiEndpoint);
        $this->assertEquals(2, $vault->logLevel);
        $this->assertEquals(10, $vault->timeout);
        $this->assertFalse($vault->sslVerifyHost);

        $this->assertInstanceOf('\Connect\Model', $vault->service);
        $this->assertEquals('some-key', $vault->service->key);
        $this->assertEquals('some-secret', $vault->service->secret);

        $varDumpOutput = $vault->__debugInfo();

        $this->assertEquals('********************************', $varDumpOutput['token']);
        $this->assertEquals('http://some-url', $varDumpOutput['uri']);
        $this->assertEquals('/for/vault/hash', $varDumpOutput['path']);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing properties: token, uri, path.
     */
    public function testInstantiationMissingProperties()
    {
        new \Connect\Config\VaultConfig([]);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Error obtaining configuration.
     */
    public function testFailedRequest()
    {
        $client = $this->getMockedHttpClient(__DIR__ . '/ok.http.json', 404);

        new \Connect\Config\VaultConfig([
            'token' => 'some-token',
            'uri' => 'http://some-url/',
            'path' => '/for/vault/hash'
        ], $client);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @expectedException \Exception
     */
    public function testFailedRequestNoDependencyInjection()
    {
        new \Connect\Config\VaultConfig([
            'token' => 'some-token',
            'uri' => 'http://some-url/',
            'path' => '/for/vault/hash'
        ]);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Wrong formatted configuration obtained from Vault.
     */
    public function testInvalidResponseData()
    {
        $client = $this->getMockedHttpClient(__DIR__ . '/malformed.http.json', 200);

        new \Connect\Config\VaultConfig([
            'token' => 'some-token',
            'uri' => 'http://some-url/',
            'path' => '/for/vault/hash'
        ], $client);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing required property token.
     */
    public function testInstantiationFailMissingToken()
    {
        new \Connect\Config\VaultConfig([
            'token' => '',
            'uri' => 'http://some-url/',
            'path' => '/for/vault/hash'
        ]);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing required property uri.
     */
    public function testInstantiationFailMissingUri()
    {
        new \Connect\Config\VaultConfig([
            'token' => 'some-token',
            'uri' => '',
            'path' => '/for/vault/hash'
        ]);
    }

    /**
     * @throws \Connect\ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Missing required property path.
     */
    public function testInstantiationFailMissingPath()
    {
        new \Connect\Config\VaultConfig([
            'token' => 'some-token',
            'uri' => 'http://some-url/',
            'path' => ''
        ]);
    }
}