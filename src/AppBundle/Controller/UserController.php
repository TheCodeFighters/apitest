<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns of n Messages of twitter for this user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Message::class, groups={"full"})
     *     )
     * )
     * @SWG\Parameter(
     *     name="provider",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="The field used to select provider for the query"
     * )
     * @SWG\Parameter(
     *     name="username",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="The field used to select username"
     * )
     * @SWG\Parameter(
     *     name="numberOfMessages",
     *     in="query",
     *     required=true,
     *     type="integer",
     *     description="The field used to select number of messages returned by the endpoint"
     * )
     * @SWG\Tag(name="message", description="describe a Message Object with id and text")
     * @param string $username
     * @param int $numberOfMessages
     * @param string $provider
     * @param ParamFetcher $paramFetcher
     * @return AppBundle\Entity\Message[]
     */
    public function getUserMessagesAction( string $provider,string $username,ParamFetcher $paramFetcher): array
    {
        $dynamicQueryParam = new QueryParam();
        $dynamicQueryParam->name = "numberOfMessages";
        $dynamicQueryParam->requirements="\d+";
        $paramFetcher->addParam($dynamicQueryParam);
        $service = $this->container->get('app.message_service');
        $messages = $service->getUserMessages($username, $paramFetcher->get('numberOfMessages'));
        return $messages;
    }
}
