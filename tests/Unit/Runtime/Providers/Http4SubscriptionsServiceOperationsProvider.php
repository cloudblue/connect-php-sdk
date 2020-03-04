<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\Runtime\Providers;

use Connect\Runtime\ServiceProvider;
use Pimple\Container;

class Http4SubscriptionsServiceOperationsProvider extends ServiceProvider
{
    public function register(Container $container)
    {
        $body = \Mockery::mock('\Psr\Http\Message\StreamInterface');
        $body->shouldReceive('getContents')
            ->andReturn(
                trim("[".file_get_contents(__DIR__ . '/subscriptionRequest.json')."]"),
                trim("[".file_get_contents(__DIR__ . '/subscriptionRequest.json')."]"),
                trim("[".file_get_contents(__DIR__ . '/subscriptionRequest.json')."]"),
                trim("[".file_get_contents(__DIR__ . '/subscriptionAsset.json')."]"),
                trim("[".file_get_contents(__DIR__ . '/subscriptionAsset.json')."]"),
                trim("[".file_get_contents(__DIR__ . '/subscriptionAsset.json')."]"),
                trim(file_get_contents(__DIR__ . '/subscriptionAsset.json'))
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
