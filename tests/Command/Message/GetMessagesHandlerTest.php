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

    private $messageService;
    private $messageInfo;
    private $command;
    private $cache;
    private $cacheItem;
    private $eventDispatcher;
    private $serializer;

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
        $this->messageService->expects($this->any())
            ->method('getMessagesByUsernameAndNumberOfMessages')
            ->will($this->returnValue($this->messageInfo));

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
    }

    public function testHandleWithCache()
    {

        $this->cacheItem->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(false));

        $getMessagesHandler = new GetMessagesHandler($this->cache,$this->messageService,$this->eventDispatcher);
        $output =  $getMessagesHandler->handle($this->command);
        $messageRequest = new MessageRequest($this->command);
        $message = new Message($this->messageInfo[0]["id"],$this->messageInfo[0]["full_text"],$messageRequest);

        $this->assertEquals($this->serializer->serialize(array($message), 'json'),$this->serializer->serialize($output, 'json'));
    }

//    public function testHandleWithoutCache()
//    {
//        $messageService = $this->getMockBuilder('App\Service\Message\MessageServiceInterface')
//            ->getMockForAbstractClass();
//        $messageInfo = array(
//            array(
//                "full_text" => "mensaje de ejemplo",
//                "id" => 1
//            )
//        );
//
//        $command = $this->createMock(GetMessagesCommand::class);
//        $command->expects($this->any())
//            ->method('getNumberOfMessages');
//        $command->expects($this->any())
//            ->method('getUsername');
//
//        $cache = $this->createMock(AdapterInterface::class);
//        $cacheItem = $this->getMockBuilder(CacheItemInterface::class)
//            ->getMockForAbstractClass();
//        $cacheItem->expects($this->once())
//            ->method('isHit')
//            ->will($this->returnValue(true));
//
//        $cache->expects($this->any())
//            ->method('getItem')
//            ->will($this->returnValue($cacheItem));
//
//        $cacheItem->expects($this->once())
//            ->method('get')
//            ->will($this->returnValue($messageInfo));
//
//
//
//        $eventDispatcher= $this->createMock(EventDispatcher::class);
//
//        $getMessagesHandler = new GetMessagesHandler($cache,$messageService,$eventDispatcher);
//        $output =  $getMessagesHandler->handle($command);
//        $messageRequest = new MessageRequest($command);
//        $message = new Message($messageInfo[0]["id"],$messageInfo[0]["full_text"],$messageRequest);
//
//        $serializer = SerializerBuilder::create()->build();
//        $this->assertEquals($serializer->serialize(array($message), 'json'),$serializer->serialize($output, 'json'));
//    }

}