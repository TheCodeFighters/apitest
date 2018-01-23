<?php
namespace App\Service\Log;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message;
use App\Entity\Log\TwitterRequest;
use App\Command\GetMessagesCommand;


class LogService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function persistTwitterRequest(GetMessagesCommand $getMessagesCommand,array $messages)
    {
        $twitterRequest = new TwitterRequest($getMessagesCommand, $messages);
        $this->em->persist($twitterRequest);
        $this->em->flush();
    }
}