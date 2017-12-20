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


/**
 * @RouteResource("Provider", pluralize=false)
 * Class MesssageController
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    /**
     *  @SWG\Response(
     *      response=200,
     *      description="Returned Array of n Messages determined by numberOfMessages for provider and user",
     *      @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref="#/definitions/message")
     *      ),
     *  ),
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
        $service = $this->container->get('app.message_service');
        $messages = $service->getUserMessages($username, $request->query->get('numberOfMessages'));
        return $messages;
    }
}
