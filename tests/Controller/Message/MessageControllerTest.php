<?php

namespace App\Tests\Controller\Message;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class MessageControllerTest extends WebTestCase
{

    public function testGetMessages()
    {
        $client = static::createClient();
        $client->request('GET', '/api/message/letsbonus/messages?numberOfMessages=1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}