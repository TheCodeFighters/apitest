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
    private $cache;
    private $cacheItem;
    private $serializer;
    private $eventDispatcher;
    private $getMessagesHandler;
    private $messageService;
    private $message;

    public function setUp()
    {
        $this->messageService = $this->getMockBuilder('App\Service\Message\MessageServiceInterface')
            ->getMockForAbstractClass();
        $this->messageInfo = array(
            array(
                "full_text" => "mensaje de ejemplo",
                "id" => 1
            )
        );


        $this->command = $this->createMock(GetMessagesCommand::class);
        $this->command->expects($this->any())
            ->method('getNumberOfMessages');
        $this->command->expects($this->any())
            ->method('getUsername');

        $this->cache = $this->createMock(AdapterInterface::class);
        $this->cacheItem = $this->getMockBuilder(CacheItemInterface::class)
            ->getMockForAbstractClass();
        $this->cache->expects($this->any())
            ->method('getItem')
            ->will($this->returnValue($this->cacheItem));
        $this->eventDispatcher= $this->createMock(EventDispatcher::class);
        $this->serializer = SerializerBuilder::create()->build();

        $messageRequest = new MessageRequest($this->command);
        $this->message = new Message($this->messageInfo[0]["id"],$this->messageInfo[0]["full_text"],$messageRequest);
    }

    public function testHandleWithoutCache()
    {
        $this->cacheItem->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(false));
        $this->messageService->expects($this->once())
            ->method('getMessagesByUsernameAndNumberOfMessages')
            ->will($this->returnValue($this->messageInfo));
        $this->getMessagesHandler = new GetMessagesHandler($this->cache,$this->messageService,$this->eventDispatcher);
        $this->assertEquals($this->serializer->serialize(array($this->message), 'json'),$this->serializer->serialize($this->getMessagesHandler->handle($this->command), 'json'));
    }

    public function testHandleWithCache()
    {
        $this->cacheItem->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(true));
        $this->cacheItem->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->messageInfo));
        $this->messageService->expects($this->any())
            ->method('getMessagesByUsernameAndNumberOfMessages');
        $this->getMessagesHandler = new GetMessagesHandler($this->cache,$this->messageService,$this->eventDispatcher);

        $this->assertEquals($this->serializer->serialize(array($this->message), 'json'),$this->serializer->serialize($this->getMessagesHandler->handle($this->command), 'json'));

    }


}