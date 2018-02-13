<?php

namespace App\Entity\Message;

use Doctrine\ORM\Mapping as ORM;
use App\Command\Message\GetMessagesCommand;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var \App\Command\Message\GetMessagesCommand
     * @ORM\Column(type="object",nullable=false)
     */
    private $getMessagesCommand;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection;
     * @ORM\OneToMany(targetEntity="App\Entity\Message\Message", mappedBy="messageRequest",cascade={"persist", "remove"})
     */
    private $messages;

    /**
     * MessageRequest constructor.
     * @param GetMessagesCommand $getMessagesCommand
     */
    public function __construct(GetMessagesCommand $getMessagesCommand)
    {
        $this->getMessagesCommand = $getMessagesCommand;
        $this->messages = new ArrayCollection();
    }

    /**
     * @return \App\Entity\Message\Message[]
     */
    public function getMessages(): array
    {
        $return = $this->messages->toArray();
        return $return;
    }

    /**
     * @param Message $message
     */
    public function addMessage(Message $message)
    {
        $this->messages->add($message);
    }
}
