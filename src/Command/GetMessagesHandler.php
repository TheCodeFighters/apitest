<?php
namespace App\Command;
use App\Command\GetMessagesCommand;
use App\Entity\Message;
use App\Service\TwitterMessageService;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class GetMessagesHandler
{


    private $twitterMessageService;
    private $cache;

    public function __construct(AdapterInterface $cache, TwitterMessageService $twitterMessageService)
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
        return $messages;
    }

}