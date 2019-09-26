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
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $instance_id;
    /**
     * @var string
     */
    public $created;
    /**
     * @var string
     */
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
     * @param string $message
     * @return array|Model
     * @throws ConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addMessage($message)
    {
        if (isset($this->id)) {
            if ($this->__checkLastMessageIsNotEqueal($message)) {
                $request = ConnectClient::getInstance()->fulfillment->sendRequest(
                    'POST',
                    "/conversations/" . $this->id . "/messages",
                    json_encode(array("text" => $message))
                );
                return Model::modelize('conversationMessage', json_decode($request));
            }
        }
    }

    private function __checkLastMessageIsNotEqueal($message)
    {
        if (count($this->messages) == 0) {
            return true;
        } elseif (end($this->messages)->text == $message) {
            return false;
        }
        return true;
    }
}
