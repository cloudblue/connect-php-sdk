<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit\Runtime\Providers;

use Connect\Runtime\ServiceProvider;
use Pimple\Container;

class Http4DirectorygetProductServiceProvider extends ServiceProvider
{
    public function register(Container $container)
    {
        $body = \Mockery::mock('\Psr\Http\Message\StreamInterface');
        $body->shouldReceive('getContents')
            ->andReturn(
                trim(file_get_contents(__DIR__ . '/productGetRequest.json')),
                trim(file_get_contents(__DIR__.'/producttemplates.json')),
                trim(file_get_contents(__DIR__. '/productConfigurations.json')),
                trim(file_get_contents(__DIR__. '/productMedia.json')),
                trim(file_get_contents(__DIR__.'/productItems.json')),
                trim(file_get_contents(__DIR__.'/productAgreements.json')),
                trim(file_get_contents(__DIR__.'/productActions.json')),
                trim(file_get_contents(__DIR__.'/productActionLink.json'))
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
