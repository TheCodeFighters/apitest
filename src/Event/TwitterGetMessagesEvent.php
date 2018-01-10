<?php
namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Command\GetMessagesCommand;


class TwitterGetMessagesEvent extends Event
{
    const NAME = 'twitter.get_messages_request';

    protected $command;

    public function __construct(GetMessagesCommand $getMessagesCommand)
    {
        $this->command = $getMessagesCommand;
    }

    public function getCommand()
    {
        return $this->command;
    }
}