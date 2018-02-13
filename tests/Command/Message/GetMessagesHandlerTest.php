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




class GetMessagesHandlerTest extends TestCase
{
    public function setUp()
    {

    }

    public function testHandle()
    {
        $client = $this->getMockBuilder('App\Service\Message\MessageServiceInterface')
            ->getMockForAbstractClass();
        $messageInfo = array(
            array(
                "full_text" => "mensaje de ejemplo",
                "id" => 1
            )
        );
        $client->expects($this->once())
            ->method('getMessagesByUsernameAndNumberOfMessages')
            ->will($this->returnValue($messageInfo));



        $command = $this->createMock(GetMessagesCommand::class);
        $command->expects($this->any())
            ->method('getNumberOfMessages');
        $command->expects($this->any())
            ->method('getUsername');

        $dummy = $client->getMessagesByUsernameAndNumberOfMessages($command->getUsername(),$command->getNumberOfMessages());

        print_r($dummy);

        $messageRequest = new MessageRequest($command);


        $cache = $this->createMock(AdapterInterface::class);
        $cacheItem = $this->getMockBuilder('Psr\Cache\CacheItemInterface')
            ->getMockForAbstractClass();
        $cacheItem->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(true));
        $cache->expects($this->any())
            ->method('getItem')
            ->willReturn($cacheItem);
//        $cache->expects($this->once())
//              ->method('save');

        $eventDispatcher= $this->createMock(EventDispatcher::class);

        $getMessagesHandler = new GetMessagesHandler($cache,$client,$eventDispatcher);

        $expectedReturnByGetMessagesHandler = array(
            new Message($messageInfo[0]['id'],$messageInfo[0]['full_text'],$messageRequest),
        );
        $this->assertEquals($expectedReturnByGetMessagesHandler, $getMessagesHandler->handle($command));
    }

}