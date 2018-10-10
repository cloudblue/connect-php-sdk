<?php

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