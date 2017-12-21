<?php
namespace AppBundle\Command;
use AppBundle\Command\GetMessagesCommand;
use AppBundle\Entity\Message;
use AppBundle\Service\TwitterMessageService;

class GetMessagesHandler
{


    private $twitterMessageService;
    private $options;

    public function __construct(TwitterMessageService $twitterMessageService)
    {
        $this->twitterMessageService = $twitterMessageService;
    }

    /**
     * @param \AppBundle\Command\GetMessagesCommand $getMessagesCommand
     * @return Message[]
     */
    public function handle(GetMessagesCommand $getMessagesCommand): array
    {
        $responseJson = $this->twitterMessageService->getMessagesByUsernameAndNumberOfMessages($getMessagesCommand->getUsername(),$getMessagesCommand->getNumberOfMessages());
        $messages = [];
        foreach ($responseJson as $completeMessageInfo) {
            $text = $completeMessageInfo['full_text'];
            $message = new Message($completeMessageInfo['id'],$text);
            $messages[] = $message;
        }
        return $messages;
    }

}