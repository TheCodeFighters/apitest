<?php
namespace AppBundle\Service;
use AppBundle\Entity\Message;
use GuzzleHttp\Client as GuzzleClient;

class TwitterMessageService
{
    private $twitterOptions = [];
    private $httpClient;

    /**
     * TwitterMessageService constructor.
     * @param GuzzleClient $httpClient
     * @param array $twitterOptions
     * @param AdapterInterface $cache
     */
    public function __construct(GuzzleClient $httpClient, array $twitterOptions)
    {
        $this->httpClient = $httpClient;
        $this->twitterOptions = $twitterOptions;
    }

    /**
     * @param string $username
     * @param int $numberOfMessage
     * @return array
     */
    public function getMessagesByUsernameAndNumberOfMessages(string $username,int $numberOfMessage): array
    {

        $query = "statuses/user_timeline.json?screen_name=".$username."&count=".$numberOfMessage.
            "&exclude_replies=".$this->twitterOptions['user_timeline']['exclude_replies'].
            "&include_rts=".$this->twitterOptions['user_timeline']['include_rts'].
            "&tweet_mode=extended";
        $twitterResponse = $this->httpClient->get($query);
        $jsonTwitterResponse = $twitterResponse->json();

        return $jsonTwitterResponse;
    }

}