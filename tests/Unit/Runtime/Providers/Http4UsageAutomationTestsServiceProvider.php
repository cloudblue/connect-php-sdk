<?php

namespace Test\Unit\Runtime\Providers;

use Connect\Runtime\ServiceProvider;
use Pimple\Container;

/**
 * Class Http4UsageAutomationTestsServiceProvider
 * @package Test\Unit\Runtime\Providers
 */
class Http4UsageAutomationTestsServiceProvider extends ServiceProvider
{
    public function register(Container $container)
    {
        $body = \Mockery::mock('\Psr\Http\Message\StreamInterface');
        $body->shouldReceive('getContents')
            ->times(6)
            ->andReturn(trim(file_get_contents(__DIR__ . '/request.valid.usageautomation.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.usageautomationcreatefile.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.usageautomationcreatefile.json')));

        $response = \Mockery::mock('\Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')
            ->andReturn(200,200,200,201,500);

        $response->shouldReceive('getBody')
            ->andReturn($body);

        $client = \Mockery::mock('Client');
        $client->shouldReceive('request')
            ->withAnyArgs()
            ->andReturn($response);

        return $client;
    }
}