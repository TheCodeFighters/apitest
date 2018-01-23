<?php
namespace App\Service\Log;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message\MessageRequest;


class LogService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param \App\Entity\Message\RequestMessage $requestMessage
     */
    public function persistMessageRequest(MessageRequest $requestMessage)
    {
        $this->em->persist($requestMessage);
        $this->em->flush();
    }
}