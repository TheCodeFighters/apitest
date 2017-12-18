<?php

namespace AppBundle\Factory;

use AppBundle\Service\MessageServiceInterface;
use AppBundle\Service\TwitterMessageService;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageServiceStaticFactory
{
    /**
     * Build a Message Service from the given provider
     *
     * @param RequestStack $requestStack
     * @param Container $container
     * @return MessageServiceInterface
     */
    public static function createMessageService(RequestStack $requestStack, Container $container): MessageServiceInterface
    {
        $provider = $requestStack->getCurrentRequest()->get('provider');

        switch ($provider) {
            case 'twitter':
                $options = $container->getParameter('message_service.options');
                $httpClient = $container->get('guzzle.twitter.client');
                $twitterOptions = array(
                    'user_timeline' => array(
                        'exclude_replies' => $container->getParameter(
                            'twitter.statuses.user_timeline.exclude_replies'
                        ),
                        'include_rts' => $container->getParameter(
                            'twitter.statuses.user_timeline.include_rts'
                        )
                    )
                );

                $service = new TwitterMessageService($httpClient, $twitterOptions, $options);
                break;
            default:
                throw new NotFoundHttpException("Provider not found");
        }

        return $service;
    }

}

/* at the beggining I was using the ContainerAwareTrait in the services but I though it breaks the DI purpose,
   so I created the methods setHttpClient and setDefaultOptions so the service don't need the container */