<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\Request;


class RequestTest extends \Test\TestCase
{
    public function testgetNewItems()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.json'));
        $request = new Request($requests[0]);

        $this->assertCount(2,$request->getNewItems());

        return $request;
    }

    public function testgetChangedItems()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.json'));
        $request = new Request($requests[0]);

        $this->assertCount(2,$request->getChangedItems());

        return $request;
    }

    public function testgetRemovedItems()
    {
        $requests = json_decode(file_get_contents(__DIR__ . '/request.valid.json'));
        $request = new Request($requests[0]);

        $this->assertCount(1,$request->getRemovedItems());

        return $request;
    }
}
