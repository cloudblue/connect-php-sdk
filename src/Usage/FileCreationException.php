<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class FileCreationException
 *
 * @package Connect
 */
class FileCreationException extends \Connect\Exception
{
    public function __construct($message)
    {
        parent::__construct($message, 'filecreation');
    }
}
