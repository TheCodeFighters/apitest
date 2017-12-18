<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Service\MessageServiceInterface;
use AppBundle\Service\TwitterMessageService;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Stream\Stream as GuzzleStream;
use GuzzleHttp\Message\Response as GuzzleResponse;

class TwitterMessageServiceTest extends KernelTestCase
{
    private $container;
    private $options;
    private $serviceBeingTested;
    private $serviceReflected;

    public function testInstanceOfMessageServiceInterface()
    {
        $this->assertInstanceOf(MessageServiceInterface::class, $this->serviceBeingTested);
    }

    public function testTextModifiersArePhpFunctions()
    {
        $this->assertInternalType('array', $this->options);

        if (array_key_exists('text_modifiers', $this->options)) {
            foreach ($this->options['text_modifiers'] as $modifier) {
                $this->assertTrue(function_exists($modifier));
            }
        }
    }

    public function testExtractTextFromMessages()
    {
        $mockedMessages = array(
            $this->mockMessage(1, 'First mocked Message'),
            $this->mockMessage(2, 'Second mocked Message')
        );

        $method = $this->getPrivateMethod('extractTextFromMessages');
        $texts = $method->invokeArgs($this->serviceBeingTested, array($mockedMessages));

        $this->assertInternalType('array', $texts);
        $this->assertEquals(2, count($texts));
        $this->assertInternalType('string', $texts[0]);
        $this->assertInternalType('string', $texts[1]);
    }

    public function testExtractMessagesFromResponse()
    {
        $mockedResponse = $this->mockGuzzleResponse(
            200,
            ['Content-Type' => 'application/json'],
            [
                [
                    'id' => '111111',
                    'full_text' => 'First mocked returned tweet'
                ],
                [
                    'id' => '222222',
                    'full_text' => 'Second mocked returned tweet'
                ]
            ]
        );

        $method = $this->getPrivateMethod('extractMessagesFromResponse');
        $messages = $method->invokeArgs($this->serviceBeingTested, array($mockedResponse->json()));

        $this->assertInternalType('array', $messages);
        $this->assertEquals(2, count($messages));
        $this->assertInstanceOf(Message::class, $messages[0]);
        $this->assertInstanceOf(Message::class, $messages[1]);
    }

    protected function getPrivateMethod($name) {
        $method = $this->serviceReflected->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected function mockMessage($id, $text)
    {
        $mockedMessage = new Message();
        $mockedMessage->setId($id);
        $mockedMessage->setText($text);

        return $mockedMessage;
    }

    protected function mockGuzzleResponse($statusCode, $params, $content)
    {
        $string = json_encode($content);
        $body = GuzzleStream::factory($string);
        $response = new GuzzleResponse($statusCode, $params, $body);

        return $response;
    }

    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();

        $this->options = $this->container->getParameter('message_service.options');

        $this->serviceBeingTested = new TwitterMessageService(new GuzzleClient(), [], []);

        $this->serviceReflected = new \ReflectionClass('AppBundle\Service\TwitterMessageService');
    }
}
