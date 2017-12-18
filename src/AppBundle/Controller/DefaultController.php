<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @RouteResource("Apitest", pluralize=false)
 */
class DefaultController extends Controller
{
    /**
     * Get a json array with the lasts n posts of a user in a given provider
     *
     * @param string $provider
     * @param string $username
     * @param int $numberOfMessages
     * @return string
     */
    public function getUserMessagesAction(string $provider, string $username, int $numberOfMessages)
    {
        $service = $this->container->get('app.message_service');

        return $service->getUserMessages($username, $numberOfMessages);
    }
}
