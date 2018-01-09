<?php
namespace App\Command;
use App\Entity\Message;

class GetMessagesCommand
{
    /**
     * @var int
     */
    private $numberOfMessages;
    /**
     * @var string
     */
    private $username;

    /**
     * GetMessagesCommand constructor.
     * @param int $numberOfMessages
     * @param string $username
     */
    public function __construct(int $numberOfMessages,string $username)
    {
        $this->numberOfMessages = $numberOfMessages;
        $this->username = $username;
    }

    /**
     * @return int
     */
    public function getNumberOfMessages(): int
    {
        return $this->numberOfMessages;
    }

    /**
     * @return Message
     */
    public function getUsername(): string
    {
        return $this->username;
    }


}