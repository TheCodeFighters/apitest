<?php
namespace App\Event\EventListener;

use Symfony\Component\EventDispatcher\Event;

class TwitterEventListener
{

    public function onGetMessagesAction(Event $event)
    {
        echo ("get messages twitter listener");
    }
}