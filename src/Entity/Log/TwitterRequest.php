<?php

namespace App\Entity\Log;

use Doctrine\ORM\Mapping as ORM;
use App\Command\GetMessagesCommand;

/**
 * @ORM\Table(name="TwitterRequest")
 * @ORM\Entity(repositoryClass="App\Repository\Log\TwitterRequestRepository")
 */
class TwitterRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var App\Command\GetMessagesCommand
     * @ORM\Column(type="object",nullable=false)
     */
    private $getMessagesCommand;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="twitterRequest",cascade={"persist", "remove"})
     */
    private $messages;

    public function __construct(GetMessagesCommand $getMessagesCommand,array $messages)
    {
        $this->getMessagesCommand = $getMessagesCommand;
    }
}
