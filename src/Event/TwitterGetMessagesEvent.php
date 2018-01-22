<?php
namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Command\GetMessagesCommand;


class TwitterGetMessagesEvent extends Event
{
    const NAME = 'twitter.get_messages_request';

    protected $command;
    protected $messages;


    public function __construct(GetMessagesCommand $getMessagesCommand,array $messages)
    {
        $this->command = $getMessagesCommand;
        $this->messages = $messages;
    }

    public function getCommand(): GetMessagesCommand
    {
        return $this->command;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}