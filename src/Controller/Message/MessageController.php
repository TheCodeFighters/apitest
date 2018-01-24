<?php

namespace App\Controller\Message;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use App\Entity\Message\Message;
use Swagger\Annotations as SWG;
use League\Tactician\CommandBus;
use App\Command\Message\GetMessagesCommand;


/**
 * @RouteResource("Message", pluralize=false)
 * Class MesssageController
 * @package AppBundle\Controller
 */
class MessageController extends Controller
{


    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @SWG\Response (
     *     response=200,
     *     description="Returned Array of n Messages determined by numberOfMessages and user",
     *     @SWG\Schema (
     *              @SWG\Property(
     *              property="id",
     *              type="integer",
     *              default="success"
     *          ),
     *          @SWG\Property(
     *              property="text",
     *              type="string",
     *              default="success"
     *          ),
     *     )
     * ),
     * @SWG\Parameter(
     *     name="username",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="The field used to select username"
     * ),
     * @SWG\Parameter(
     *     name="numberOfMessages",
     *     in="query",
     *     required=true,
     *     type="integer",
     *     description="The field used to select number of messages returned by the endpoint"
     * ),
     * @param string $username
     * @param Request $request
     * @return Message[]
     */
    public function getMessagesAction(string $username, Request $request): array
    {
        $command = new GetMessagesCommand($request->query->get('numberOfMessages'),$username);
        return $this->commandBus->handle($command);
    }
}
