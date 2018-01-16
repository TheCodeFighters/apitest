<?php
namespace App\Event\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Psr\Log\LoggerInterface;

class TwitterEventListener
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger= $logger;
    }

    public function onGetMessagesAction(Event $event)
    {
        $this->logger->info('**********Mensaje de Logger desde el evento**********');
    }
}