<?php
namespace AppBundle\Command;
use AppBundle\Command\GetMessagesCommand;
use AppBundle\Entity\Message;
use AppBundle\Service\TwitterMessageService;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class GetMessagesHandler
{


    private $twitterMessageService;
    private $options;
    private $cache;

    public function __construct(TwitterMessageService $twitterMessageService, AdapterInterface $cache)
    {
        $this->twitterMessageService = $twitterMessageService;
        $this->cache = $cache;
    }

    /**
     * @param \AppBundle\Command\GetMessagesCommand $getMessagesCommand
     * @return Message[]
     */
    public function handle(GetMessagesCommand $getMessagesCommand): array
    {

        if (!$this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages())->isHit()) {
            $responseJson = $this->twitterMessageService->getMessagesByUsernameAndNumberOfMessages($getMessagesCommand->getUsername(),$getMessagesCommand->getNumberOfMessages());
            $messageCached = $this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages());
            $messageCached->set($jsonTwitterResponse);
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
        return $messages;
    }

}