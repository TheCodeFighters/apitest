<?php
namespace App\Factory\Message;
use App\Service\Message\TwitterMessageImplService;
use GuzzleHttp\Client;
class MessageHandlerStaticFactory
{
    /**
     * Build a Message Service from the given provider
     *
     * @param string $exclude_replies
     * @param string $include_rts
     * @param Client $httpClient
     * @return TwitterMessageImplService
     */
    public static function createTwitterMessageImplService(string $exclude_replies,string $include_rts,Client $httpClient): TwitterMessageImplService
    {
        $twitterOptions = array(
            'user_timeline' => array(
                'exclude_replies' => boolval($exclude_replies),
                'include_rts' => boolval($include_rts)
            )
        );
        return new TwitterMessageImplService($httpClient, $twitterOptions);
    }
}