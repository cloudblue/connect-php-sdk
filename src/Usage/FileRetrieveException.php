<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect\Usage;

/**
 * Class FileRetrieveException
 *
 * @package Connect
 */
class FileRetrieveException extends \Connect\Exception
{
    public function __construct($message)
    {
        parent::__construct($message, 'fileretrieve');
    }
}
