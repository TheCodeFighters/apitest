<?php

namespace App\Tests\Command\Message;

use PHPUnit\Framework\TestCase;
use App\Service\Message\MessageServiceInterface;
use App\Entity\Message\MessageRequest;
use App\Entity\Message\Message;
use App\Command\Message\GetMessagesHandler;
use App\Command\Message\GetMessagesCommand;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Cache\Adapter\NullAdapter;




class GetMessagesHandlerTest extends TestCase
{
    public function setUp()
    {

    }

    public function testHandle()
    {
        $client = $this->createMock(MessageServiceInterface::class);
        $messageInfo = array(
            array(
                "full_text" => "mensaje de ejemplo",
                "id" => 1
            )
        );
//        $client->expects($this->once())
//            ->method('getMessagesByUsernameAndNumberOfMessages')
//            ->will($this->returnValue($messageInfo));

        $command = $this->createMock(GetMessagesCommand::class);
        $command->expects($this->any())
            ->method('getNumberOfMessages');
        $command->expects($this->any())
            ->method('getUsername');

        $messageRequest = new MessageRequest($command);
        $expectedReturnByGetMessagesHandler = array(
            new Message($messageInfo[0]['id'],$messageInfo[0]['full_text'],$messageRequest),
        );

        $cache = new NullAdapter();
//        $cacheItem = $cache->getItem('key');
//        $cache->expects($this->once())
//              ->method('getItem')
//              ->willReturn(null);
//        $cacheItem->expects($this->once())
//              ->method('isHit')
//              ->will($this->returnValue(true));

//        $cache->expects($this->once())
//              ->method('save');

        $eventDispatcher= $this->createMock(EventDispatcher::class);
        $getMessagesHandler = new GetMessagesHandler($cache,$client,$eventDispatcher);
        var_dump($getMessagesHandler->handle($command));
        die();
        $this->assertEquals($expectedReturnByGetMessagesHandler, $getMessagesHandler->handle($command));
    }

}