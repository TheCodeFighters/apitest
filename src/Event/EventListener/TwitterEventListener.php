<?php
namespace App\Event\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Psr\Log\LoggerInterface;
use App\Command\GetMessagesCommand;
use App\Service\Log\LogService;

class TwitterEventListener
{

    private $logger;

    public function __construct(LoggerInterface $logger,LogService $logService)
    {
        $this->logger = $logger;
        $this->logService = $logService;
    }

    public function onGetMessagesAction(Event $event)
    {
        $this->logService->persistTwitterRequest($event->getCommand(),$event->getMessages);

        $this->logger->info('**********'.$event->getCommand()->getNumberOfMessages().'**********');
        $this->logger->info('**********'.$event->getCommand()->getUsername().'**********');
    }
}