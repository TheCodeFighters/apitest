<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\Model;
use AppBundle\Entity\Message;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use League\Tactician\CommandBus;
use AppBundle\Command\GetMessagesCommand;


/**
 * @RouteResource("Provider", pluralize=false)
 * Class MesssageController
 * @package AppBundle\Controller
 */
class UserController extends Controller
{


    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @SWG\Response (
     *     response=200,
     *     description="Returned Array of n Messages determined by numberOfMessages for provider and user",
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
     *     name="provider",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="The field used to select provider for the query"
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
     * @param int $numberOfMessages
     * @param string $provider
     * @param string $username
     * @param Request $request
     * @return AppBundle\Entity\Message[]
     */
    public function getUserMessagesAction( string $provider,string $username,Request $request): array
    {
        $command = new GetMessagesCommand($request->query->get('numberOfMessages'),$username);
        return $this->commandBus->handle($command);
    }
}
