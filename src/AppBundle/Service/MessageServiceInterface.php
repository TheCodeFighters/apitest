<?php

namespace AppBundle\Service;

use AppBundle\Entity\Message;

interface MessageServiceInterface
{
    /**
     * Get the last n messages posted by an user
     *
     * @param string $username
     * @param int $numberOfMessages
     * @return Message[]
     */
    public function getUserMessages(string $username, int $numberOfMessages) : array;

}