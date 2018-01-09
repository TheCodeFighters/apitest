<?php
namespace App\Factory;
use App\Service\TwitterMessageService;
use GuzzleHttp\Client;
class MessageHandlerStaticFactory
{
    /**
     * Build a Message Service from the given provider
     *
     * @param string $exclude_replies
     * @param string $include_rts
     * @param Client $httpClient
     * @return TwitterMessageService
     */
    public static function createTwitterMessageService(string $exclude_replies,string $include_rts,Client $httpClient): TwitterMessageService
    {
        $twitterOptions = array(
            'user_timeline' => array(
                'exclude_replies' => boolval($exclude_replies),
                'include_rts' => boolval($include_rts)
            )
        );
        return new TwitterMessageService($httpClient, $twitterOptions);
    }
}