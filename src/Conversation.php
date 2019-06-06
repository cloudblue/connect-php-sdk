<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2019. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Conversation
 * @package Connect
 */
class Conversation extends Model
{
    public $id;
    public $instance_id;
    public $created;
    public $topic;

    /**
     * @var RequestsProcessor
     * @noparse
     */
    public $requestProcessor;

    /**
     * @var ConversationMessage[]
     */
    public $messages;

    public function setMessages($messages)
    {
        $this->messages = Model::modelize('conversationMessages', $messages);
    }

    /**
     * @param $message
     * @return array|Model
     */
    public functaddMessage($message)
    {
        if (isset($this->id)) {
            $request = $this->requestProcessor->sendRequest(
                'POST',
                "/conversations/" . $this->id . "/messages",
                json_encode(array("text" => $message))
            );
            return Model::modelize('conversationMessage', json_decode($request));
        }
    }
}
