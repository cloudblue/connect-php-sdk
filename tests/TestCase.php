<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Return a mocked Http client (guzzle by default)
     * @param string $responseFileBodyRef
     * @param int $responseStatus
     * @return \Mockery\MockInterface
     */
    public function getMockedHttpClient($responseFileBodyRef, $responseStatus)
    {
        if (is_readable($responseFileBodyRef)) {
            $content = file_get_contents($responseFileBodyRef);
        }

        $response = \Mockery::mock('\Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')
            ->andReturn($responseStatus)
            ->shouldReceive('getBody')
            ->andReturn(isset($content) ? $content : '');

        $client = \Mockery::mock('\GuzzleHttp\Client');
        $client->shouldReceive('request')
            ->withAnyArgs()
            ->andReturn($response);

        return $client;
    }
}
