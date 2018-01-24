<?php
namespace App\Repository\Message;

use App\Entity\Message\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MessageRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TwitterRequest::class);
    }
}