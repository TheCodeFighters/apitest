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

    private $messageInfo;
    private $command;
    private $cacheItem;
    private $serializer;
    private $getMessagesHandler;
    private $message;

    public function setUp()
    {
        $messageService = $this->getMockBuilder('App\Service\Message\MessageServiceInterface')
            ->getMockForAbstractClass();
        $this->messageInfo = array(
            array(
                "full_text" => "mensaje de ejemplo",
                "id" => 1
            )
        );
        $messageService->expects($this->any())
            ->method('getMessagesByUsernameAndNumberOfMessages')
            ->will($this->returnValue($this->messageInfo));

        $this->command = $this->createMock(GetMessagesCommand::class);
        $this->command->expects($this->any())
            ->method('getNumberOfMessages');
        $this->command->expects($this->any())
            ->method('getUsername');

        $cache = $this->createMock(AdapterInterface::class);
        $this->cacheItem = $this->getMockBuilder(CacheItemInterface::class)
            ->getMockForAbstractClass();
        $cache->expects($this->any())
            ->method('getItem')
            ->will($this->returnValue($this->cacheItem));
        $eventDispatcher= $this->createMock(EventDispatcher::class);
        $this->serializer = SerializerBuilder::create()->build();
        $this->getMessagesHandler = new GetMessagesHandler($cache,$messageService,$eventDispatcher);
        $messageRequest = new MessageRequest($this->command);
        $this->message = new Message($this->messageInfo[0]["id"],$this->messageInfo[0]["full_text"],$messageRequest);
    }

    public function testHandleWithCache()
    {
        $this->cacheItem->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(false));
        $this->assertEquals($this->serializer->serialize(array($this->message), 'json'),$this->serializer->serialize($this->getMessagesHandler->handle($this->command), 'json'));
    }

    public function testHandleWithoutCache()
    {
        $this->cacheItem->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(true));
        $this->cacheItem->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->messageInfo));
        $this->assertEquals($this->serializer->serialize(array($this->message), 'json'),$this->serializer->serialize($this->getMessagesHandler->handle($this->command), 'json'));

    }


}