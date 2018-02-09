<?php

namespace App\Tests\Service\Log;

use PHPUnit\Framework\TestCase;
use App\Entity\Message\MessageRequest;
use App\Repository\Message\MessageRequestRepository;
use App\Service\Log\LogService;


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