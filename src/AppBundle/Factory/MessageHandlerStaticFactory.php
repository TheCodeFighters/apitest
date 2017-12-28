<?php
namespace AppBundle\Factory;
use AppBundle\Service\TwitterMessageService;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class MessageHandlerStaticFactory
{
    /**
     * Build a Message Service from the given provider
     *
     * @param Container $container
     * @return TwitterMessageService
     */
    public static function createTwitterMessageService(Container $container): TwitterMessageService
    {
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
        return new TwitterMessageService($httpClient, $twitterOptions);
    }
}