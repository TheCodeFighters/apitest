<?php
namespace App\Command;
use App\Command\GetMessagesCommand;
use App\Entity\Message;
use App\Service\TwitterMessageService;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use App\Event\TwitterGetMessagesEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\Event\EventListener\TwitterEventListener;
use Psr\Log\LoggerInterface;



class GetMessagesHandler
{


    private $twitterMessageService;
    private $cache;
    private $dispatcher;

    public function __construct(AdapterInterface $cache, TwitterMessageService $twitterMessageService,LoggerInterface $logger)
    {
        $this->twitterMessageService = $twitterMessageService;
        $this->cache = $cache;
        $this->dispatcher = new EventDispatcher();
        $twitterListener = new TwitterEventListener($logger);
        $this->dispatcher->addListener('twitter.get_messages_request', array($twitterListener, 'onGetMessagesAction'));
    }

    /**
     * @param \App\Command\GetMessagesCommand $getMessagesCommand
     * @return Message[]
     */
    public function handle(GetMessagesCommand $getMessagesCommand): array
    {

        if (!$this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages())->isHit()) {
            $responseJson = $this->twitterMessageService->getMessagesByUsernameAndNumberOfMessages($getMessagesCommand->getUsername(),$getMessagesCommand->getNumberOfMessages());
            $messageCached = $this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages());
            $messageCached->set($responseJson);
            $this->cache->save($messageCached);
        } else {
            $responseJson = $this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages())->get();
        }

        $messages = [];
        foreach ($responseJson as $completeMessageInfo) {
            $text = $completeMessageInfo['full_text'];
            $message = new Message($completeMessageInfo['id'],$text);
            $messages[] = $message;
        }
        // creo el evento y lo sirvo
        $event = new TwitterGetMessagesEvent($getMessagesCommand,$messages);
        $this->dispatcher->dispatch(TwitterGetMessagesEvent::NAME, $event);

        return $messages;
    }

}