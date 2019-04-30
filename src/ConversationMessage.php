<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ConversationMessage
 * @package Connect
 */
class ConversationMessage extends Model
{
    public $id;
    public $conversation;
    public $created;
    public $text;
    public $type;

    /**
     * @var Owner
     */
    public $creator;
}
