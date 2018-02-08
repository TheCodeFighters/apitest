<?php

namespace App\Tests\Controller\Message;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class MessageControllerTest extends WebTestCase
{


    public function testGetMessagesAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/message/letsbonus/messages?numberOfMessages=1');
        var_dump($client->getResponse());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}