<?php

namespace App\Entity\Message;

use Doctrine\ORM\Mapping as ORM;
use App\Command\Message\GetMessagesCommand;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Table(name="MessageRequest")
 * @ORM\Entity(repositoryClass="App\Repository\Message\MessageRequestRepository")
 */
class MessageRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var App\Command\Message\GetMessagesCommand
     * @ORM\Column(type="object",nullable=false)
     */
    private $getMessagesCommand;

    /**
     * @var Doctrine\ORM\PersistentCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Message\Message", mappedBy="messageRequest",cascade={"persist", "remove"})
     */
    private $messages;

    /**
     * MessageRequest constructor.
     * @param GetMessagesCommand $getMessagesCommand
     * @param App\Entity\Message\Message[] $messages
     */
    public function __construct(GetMessagesCommand $getMessagesCommand,array $messages = NULL)
    {
        $this->getMessagesCommand = $getMessagesCommand;
        if($messages === NULL) {
            $this->messages = array();
        }else{
            $this->messages = $messages;
        }
    }

    /**
     * @return App\Entity\Message\Message[]
     */
    public function getMessages(): array
    {
       return $this->messages->toArray();
    }

    public function addMessage(Message $message)
    {
        array_push($this->messages,$message);
    }
}
