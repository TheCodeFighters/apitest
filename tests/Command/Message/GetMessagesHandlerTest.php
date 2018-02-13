<?php

namespace App\Tests\Command\Message;

use PHPUnit\Framework\TestCase;
use App\Service\Message\MessageServiceInterface;
use App\Entity\Message\MessageRequest;
use App\Entity\Message\Message;
use App\Command\Message\GetMessagesHandler;
use App\Command\Message\GetMessagesCommand;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Psr\Cache\CacheItemInterface;
use JMS\Serializer\SerializerBuilder;




class GetMessagesHandlerTest extends TestCase
{
    public function setUp()
    {

    }

    public function testHandle()
    {
        $messageService = $this->getMockBuilder('App\Service\Message\MessageServiceInterface')
            ->getMockForAbstractClass();
        $messageInfo = array(
            array(
                "full_text" => "mensaje de ejemplo",
                "id" => 1
            )
        );
        $messageService->expects($this->any())
            ->method('getMessagesByUsernameAndNumberOfMessages')
            ->will($this->returnValue($messageInfo));

        $command = $this->createMock(GetMessagesCommand::class);
        $command->expects($this->any())
            ->method('getNumberOfMessages');
        $command->expects($this->any())
            ->method('getUsername');

        $cache = $this->createMock(AdapterInterface::class);


        $cacheItem = $this->getMockBuilder(CacheItemInterface::class)
            ->getMockForAbstractClass();
        $cacheItem->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(false));

        $cache->expects($this->any())
            ->method('getItem')
            ->will($this->returnValue($cacheItem));
        $eventDispatcher= $this->createMock(EventDispatcher::class);

        $getMessagesHandler = new GetMessagesHandler($cache,$messageService,$eventDispatcher);
        $output =  $getMessagesHandler->handle($command);

        $messageRequest = new MessageRequest($command);

        $message = new Message($messageInfo[0]["id"],$messageInfo[0]["full_text"],$messageRequest);


        $serializer = SerializerBuilder::create()->build();
        $this->assertEquals($serializer->serialize(array($message), 'json'),$serializer->serialize($output, 'json'));
    }

}