<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;


use Connect\ActivationTemplateResponse;

/**
 * Class ActivationTemplateResponseTest
 * @package Tests\Unit
 */
class ActivationTemplateResponseTest extends \Test\TestCase
{

    /**
     * @return ActivationTemplateResponse
     */
    public function testInstantiation()
    {
        $responseTemplate = new ActivationTemplateResponse('TD-123');

        $this->assertInstanceOf('\Connect\ActivationTemplateResponse', $responseTemplate);

        $this->assertEquals("TD-123", $responseTemplate->templateid);
        return $responseTemplate;
    }

}