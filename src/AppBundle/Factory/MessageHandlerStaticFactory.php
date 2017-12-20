<?php
namespace AppBundle\Factory;
use AppBundle\Command\GetMessagesHandler;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class MessageHandlerStaticFactory
{
    /**
     * Build a Message Service from the given provider
     *
     * @param RequestStack $requestStack
     * @param Container $container
     * @return MessageServiceInterface
     */
    public static function createGetMessageHandler(RequestStack $requestStack, Container $container): GetMessagesHandler
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
                $service = new GetMessagesHandler($httpClient, $twitterOptions, $options);
                break;
            default:
                throw new NotFoundHttpException("Provider not found");
        }
        return $service;
    }
}