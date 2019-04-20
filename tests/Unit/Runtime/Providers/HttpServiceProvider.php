<?php

namespace Test\Unit\Runtime\Providers;

use Connect\Runtime\ServiceProvider;
use Pimple\Container;

/**
 * Class HttpServiceProvider
 * @package Test\Unit\Runtime\Providers
 */
class HttpServiceProvider extends ServiceProvider
{
    public function register(Container $container)
    {
        $body = \Mockery::mock('\Psr\Http\Message\StreamInterface');

        $body->shouldReceive('getContents')
            ->times(15)
            ->andReturn(
                trim(file_get_contents(__DIR__ . '/request.valid.TierConfig.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.TierConfig.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.TierConfig.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.TierConfig.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.TierConfig.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.TierConfig.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.TierConfig.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.fulfillment.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.fulfillment.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.fulfillment.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.fulfillment.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.fulfillment.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.fulfillment.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.fulfillment.json')),
                trim(file_get_contents(__DIR__ . '/request.valid.fulfillment.json'))
            );

        $response = \Mockery::mock('\Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')
            ->andReturn(200);

        $response->shouldReceive('getBody')
            ->andReturn($body);

        $client = \Mockery::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('request')
            ->withAnyArgs()
            ->andReturn($response);

        return $client;
    }
}
