<?php
namespace App\Service\Log;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Message\MessageRequest;
use App\Repository\Message\MessageRequestRepository;
use Doctrine\ORM\EntityRepository;


class LogService
{
    private $messageRequestRepository;

    public function __construct(EntityRepository $messageRequestRepository)
    {
        $this->messageRequestRepository = $messageRequestRepository;
    }

    /**
     * @param \App\Entity\Message\RequestMessage $messageRequest
     */
    public function persistMessageRequest(MessageRequest $messageRequest)
    {
        $this->messageRequestRepository->save($messageRequest);
    }
}