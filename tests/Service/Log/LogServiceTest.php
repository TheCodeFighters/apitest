<?php
/**
 * Created by PhpStorm.
 * User: oscarlopez1616
 * Date: 7/02/18
 * Time: 13:39
 */

namespace App\Tests\Service\Log;

use App\Repository\Message\MessageRequestRepository;
use PHPUnit\Framework\TestCase;
use App\Entity\Message\MessageRequest;
use App\Entity\Message\Message;
use App\Repository\Message\MessageRequestRepository;
use App\Command\Message\GetMessagesCommand;
use App\Service\Log\LogService;
use Doctrine\ORM\EntityRepository;


class LogServiceTest extends TestCase
{
    public function testPersistMessageRequest()
    {
        $getMessagesCommand  = new GetMessagesCommand(1,'letsbonus');

        $messageRequest = new MessageRequest($getMessagesCommand);
        $message = new Message(0,"texto mockeado0",$messageRequest);
        $messageRequest->addMessage($message);
        $message = new Message(1,"texto mockeado1",$messageRequest);
        $messageRequest->addMessage($message);


        // Now, mock the repository so it returns the mock of the employee
        $messageRequestRepository = $this->createMock(MessageRequestRepository::class);
        // use getMock() on PHPUnit 5.3 or below
        // $employeeRepository = $this->getMock(ObjectRepository::class);
        $messageRequestRepository->expects($this->any())
            ->method('save')
            ->willReturn($employee);

        // Last, mock the EntityManager to return the mock of the repository
        $objectManager = $this->createMock(ObjectManager::class);
        // use getMock() on PHPUnit 5.3 or below
        // $objectManager = $this->getMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($messageRequestRepository);

        $salaryCalculator = new SalaryCalculator($objectManager);
        $this->assertEquals(2100, $salaryCalculator->calculateTotalSalary(1));





        $logService = $this->getMockBuilder('App\Service\Log\LogService')
            ->setConstructorArgs(array($messageRequest))
            ->getMock();
        $logService->persistMessageRequest($messageRequest);

        $this->assertEquals(1, 1);
    }

    private function castObject($className,$instance){
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(strstr(serialize($instance), '"'), ':')
        ));
    }
}