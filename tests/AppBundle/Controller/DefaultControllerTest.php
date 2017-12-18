<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $httpClient;

    public function testRootRouteNotAcceptable()
    {
        $this->httpClient->request('GET', '/');

        $this->assertEquals(406, $this->httpClient->getResponse()->getStatusCode());
    }

    public function testGoodRouteOkResponse()
    {
        $this->httpClient->request('GET', '/api/apitest/twitter/user/darkkz/messages/1');

        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
    }

    public function setUp()
    {
        $this->httpClient = static::createClient();
    }
}
