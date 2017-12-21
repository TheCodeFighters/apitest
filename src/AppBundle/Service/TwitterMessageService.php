<?php
namespace AppBundle\Service;
use AppBundle\Entity\Message;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class TwitterMessageService
{
    private $twitterOptions = [];
    private $httpClient;
    private $cache;

    /**
     * TwitterMessageService constructor.
     * @param GuzzleClient $httpClient
     * @param array $twitterOptions
     * @param array $options
     */
    public function __construct(GuzzleClient $httpClient, array $twitterOptions)
    {

        //$redisConnection = RedisAdapter::createConnection('redis://redis:6379');
        //$this->cache = new RedisAdapter($redisConnection, '', 60);
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
        //if (!$this->cache->getItem($username."-".$numberOfMessages)->isHit()) {
        $query = "statuses/user_timeline.json?screen_name=".$username."&count=".$numberOfMessage.
            "&exclude_replies=".$this->twitterOptions['user_timeline']['exclude_replies'].
            "&include_rts=".$this->twitterOptions['user_timeline']['include_rts'].
            "&tweet_mode=extended";
        $twitterResponse = $this->httpClient->get($query);
        $jsonTwitterResponse = $twitterResponse->json();

        /*$messageCached = $this->cache->getItem($username."-".$numberOfMessages);
        $messageCached->set($jsonTwitterResponse);
        $this->cache->save($messageCached);
    } else {
        $jsonTwitterResponse = $this->cache->getItem($username."-".$numberOfMessages)->get();
    }*/
        return $jsonTwitterResponse;
    }

}