<?php

namespace App\Tests\Service\Log;

use PHPUnit\Framework\TestCase;
use App\Entity\Message\MessageRequest;
use App\Entity\Message\Message;
use App\Repository\Message\MessageRequestRepository;
use App\Command\Message\GetMessagesCommand;
use App\Service\Log\LogService;
use Doctrine\Common\Persistence\ObjectManager;


class LogServiceTest extends TestCase
{
    private $messageRequestRepository;
    public function setUp()
    {
        $this->messageRequestRepository = $this->createMock(MessageRequestRepository::class);
    }

    public function testPersistMessageRequest()
    {
        $this->messageRequestRepository->expects($this->once())
            ->method('save')
            ->willReturn(null);
        $messageRequest = $this->createMock(MessageRequest::class);
        $logService = new LogService($this->messageRequestRepository);
        $this->assertEquals(null, $logService->persistMessageRequest($messageRequest));
    }

}