<?php
namespace App\Command\Message;

use App\Command\Message\GetMessagesCommand;
use App\Entity\Message\Message;
use App\Entity\Message\MessageRequest;
use App\Service\Message\MessageServiceInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use App\Event\Message\GetMessagesRequestEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;



class GetMessagesHandler
{
    private $messageServiceInterface;
    private $cache;
    private $dispatcher;

    /**
     * GetMessagesHandler constructor.
     * @param AdapterInterface $cache
     * @param MessageServiceInterface $messageServiceInterface
     * @param EventDispatcher $dispatcher
     */
    public function __construct(AdapterInterface $cache, MessageServiceInterface $messageServiceInterface, EventDispatcher $dispatcher)
    {
        $this->cache = $cache;
        $this->messageServiceInterface = $messageServiceInterface;
        $this->dispatcher = $dispatcher;

    }

    /**
     * @param \App\Command\Message\GetMessagesCommand $getMessagesCommand
     * @return Message[]
     */
    public function handle(GetMessagesCommand $getMessagesCommand): array
    {

        if (!$this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages())->isHit()) {
            $responseJson = $this->messageServiceInterface->getMessagesByUsernameAndNumberOfMessages($getMessagesCommand->getUsername(),$getMessagesCommand->getNumberOfMessages());
//            echo("************\n");
//            echo(get_class($this->cache));
//            echo("\n************\n");
//            echo("************\n");
//            echo(get_class($this->messageServiceInterface));
//            echo("\n************\n");
//            die();
            $messageCached = $this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages());
            $messageCached->set($responseJson);
            $this->cache->save($messageCached);
        } else {
            $responseJson = $this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages())->get();
        }
        $messageRequest = new MessageRequest($getMessagesCommand);
        var_dump($responseJson);
        foreach ($responseJson as $completeMessageInfo) {
            $text = $completeMessageInfo['full_text'];
            $message = new Message($completeMessageInfo['id'],$text,$messageRequest);
            $messageRequest->addMessage($message);
        }
        // create event and throw event
        $event = new GetMessagesRequestEvent($messageRequest);
        $this->dispatcher->dispatch(GetMessagesRequestEvent::NAME, $event);
        return $messageRequest->getMessages();
    }

}