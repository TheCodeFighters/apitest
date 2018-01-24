<?php
namespace App\Service\Message;


interface MessageServiceInterface{
    public function getMessagesByUsernameAndNumberOfMessages(string $username,int $numberOfMessage): array;
}