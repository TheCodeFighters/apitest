<?php
namespace App\Event\Message\EventListener;

use Symfony\Component\EventDispatcher\Event;
use App\Service\Log\LogService;

class MessageEventListener
{

    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function onGetMessagesAction(Event $event)
    {
        $this->logService->persistMessageRequest($event->getMessageRequest());
    }
}