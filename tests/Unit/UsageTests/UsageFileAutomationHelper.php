<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use Connect\UsageFileAutomation;

/**
 * Class UsageFileAutomationHelper
 * @property \stdClass $std
 * @package Test\Unit
 */
class UsageFileAutomationHelper extends UsageFileAutomation
{
    public function processUsageFiles($usageFile)
    {
        switch ($usageFile->id) {
            case 'UF-2019-03-6040-0448-skip':
                throw new \Connect\Usage\Skip("Skipping");
            case 'UF-2019-03-6040-0448-accept':
                throw new \Connect\Usage\Accept("Valid file moving forward");
            case 'UF-2019-03-6040-0448-close':
                throw new \Connect\Usage\Close("Closing file");
            case 'UF-2019-03-6040-0448-delete':
                throw new \Connect\Usage\Delete("Deleting due invalid file");
            case 'UF-2019-03-6040-0448-reject':
                throw new \Connect\Usage\Reject("Rejecting the file as a test");
            case 'UF-2019-03-6040-0448-submit':
                throw new \Connect\Usage\Submit("Submiting file");
            case 'UF-2019-03-6040-0448-returnweird':
                return "not really valid case";
            case 'UF-2019-03-6040-0448-returnweird2':
                $return = new \stdClass();
                $return->message = "not really valid case";
                return $return;
            default:
                throw new \Connect\Usage\Skip("Skipping");
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getHttp()
    {
        return $this->http;
    }

    public function getStd()
    {
        return $this->std;
    }
}
