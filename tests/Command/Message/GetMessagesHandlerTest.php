<?php

namespace App\Tests\Command\Message;

use PHPUnit\Framework\TestCase;


class GetMessagesHandlerTest extends TestCase
{
    public function setUp()
    {

    }

    public function testHandle()
    {
        $client = $this->getMock('App\Service\Message\MessageServiceInterface');

        // since you class is returning a log array, we mock it here
        $expectedReturn = array(
            array(
                'action'   => 'Added Stadium',
                'itemID'   => $stadium['StadiumID'],
                'itemName' => $stadium['Name']
            )
        );

        $messageData = array(
            array(
                "StadiumID" => 1,
                "Name" => "aStadiumName"
            )
        );

        $client->expects($this->once())
            ->method('getMessagesByUsernameAndNumberOfMessages')
            ->will($this->returnValue($messageData));

        $getMessagesHandler = new GetMessagesHandler($client);
        $command = $this->getMock('App\Command\Message\GetMessagesCommand');

        $this->assertEquals($expectedReturn, $getMessagesHandler->handle($command));
    }

}