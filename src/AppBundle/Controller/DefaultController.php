<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Message;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * @RouteResource("Message", pluralize=false)
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/api/message/{provider}/{username}/{numberOfMessages}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns of array Messages of twitter for this user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=AppBundle\Entity\Message::class, groups={"full"})
     *     )
     * )
     * @SWG\Parameter(
     *     name="provider",
     *     in="query",
     *     type="string",
     *     description="The field used to select provider for the query"
     * )
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     type="string",
     *     description="The field used to select username"
     * )
     * @SWG\Parameter(
     *     name="numberOfMessages",
     *     in="query",
     *     type="integer",
     *     description="The field used to select number of messages returned by the endpoint"
     * )
     * @SWG\Tag(name="usermessages")
     */
    public function getUserMessagesAction(string $provider, string $username, int $numberOfMessages): array
    {
        $service = $this->container->get('app.message_service');
        $messages = $service->getUserMessages($username, $numberOfMessages);
        return $messages;
    }
}
