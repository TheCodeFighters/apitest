<?php

namespace App\Consumer\Rabbit;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class SendHtmlMessageConsumer implements ConsumerInterface
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(AMQPMessage $msg)
    {

        $this->logger->info($msg->getBody());


        $hasBeenProcessed = true;
        if (!$hasBeenProcessed) {
            // If your image upload failed due to a temporary error you can return false
            // from your callback so the message will be rejected by the consumer and
            // requeued by RabbitMQ.
            // Any other value not equal to false will acknowledge the message and remove it
            // from the queue
            return false;
        }
    }
}