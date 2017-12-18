<?php

namespace Tests\AppBundle\Factory;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use AppBundle\Factory\MessageServiceStaticFactory;
use AppBundle\Service\MessageServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageServiceStaticFactoryTest extends KernelTestCase
{
    private $container;
    private $goodRequestStack;
    private $badRequestStack;

    public function testItReturnsMessageServiceInterface()
    {
        $messageService = MessageServiceStaticFactory::createMessageService(
            $this->goodRequestStack,
            $this->container
        );

        $this->assertInstanceOf(MessageServiceInterface::class, $messageService);
    }

    public function testThrowsNotFoundProvider()
    {
        $this->expectException(NotFoundHttpException::class);
        MessageServiceStaticFactory::createMessageService(
            $this->badRequestStack,
            $this->container
        );
    }

    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();

        $this->goodRequestStack = new RequestStack();
        $mockedRequest = new Request(
            ['GET'],
            ['api/apitest/twitter/user/darkkz/messages/1'],
            [
                'provider' => 'twitter',
                'user' => 'darkkz',
                'messages' => 1
            ],
            [],
            [],
            [],
            [],
            []
        );
        $this->goodRequestStack->push($mockedRequest);

        $this->badRequestStack = new RequestStack();
        $mockedRequest = new Request(
            ['GET'],
            ['api/apitest/fakeprovider/user/darkkz/messages/1'],
            [
                'provider' => 'fakeprovider',
                'user' => 'darkkz',
                'messages' => 1
            ],
            [],
            [],
            [],
            [],
            []
        );
        $this->badRequestStack->push($mockedRequest);
    }
}
