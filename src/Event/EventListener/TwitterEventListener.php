<?php
namespace App\Event\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Psr\Log\LoggerInterface;
use App\Command\GetMessagesCommand;

class TwitterEventListener
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger= $logger;
    }

    public function onGetMessagesAction(Event $event)
    {
        $this->logger->info('**********'.$event->getCommand()->getNumberOfMessages().'**********');
        $this->logger->info('**********'.$event->getCommand()->getUsername().'**********');
    }
}