<?php
namespace App\Event\Message;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Message\MessageRequest;

class GetMessagesRequestEvent extends Event
{
    const NAME = 'messages.get_messages_request';
    protected $messageRequest;

    public function __construct(MessageRequest $messageRequest)
    {
        $this->messageRequest = $messageRequest;
    }

    public function getMessageRequest(): MessageRequest
    {
        return $this->messageRequest;
    }
}