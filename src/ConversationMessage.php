<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class ConversationMessage
 * @package Connect
 */
class ConversationMessage extends Model
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $conversation;
    /**
     * @var string
     */
    public $created;
    /**
     * @var string
     */
    public $text;
    /**
     * @var string
     */
    public $type;

    /**
     * @var Owner
     */
    public $creator;
}
