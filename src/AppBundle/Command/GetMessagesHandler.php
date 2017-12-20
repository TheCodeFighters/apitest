<?php
namespace AppBundle\Command;
use AppBundle\Command\GetMessagesCommand;
use AppBundle\Entity\Message;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class GetMessagesHandler
{
    private $options = [];
    private $twitterOptions = [];
    private $httpClient;
    private $cache;

    /**
     * TwitterMessageService constructor.
     * @param GuzzleClient $httpClient
     * @param array $twitterOptions
     * @param array $options
     */
    public function __construct(GuzzleClient $httpClient, array $twitterOptions, array $options)
    {

        //$redisConnection = RedisAdapter::createConnection('redis://redis:6379');
        //$this->cache = new RedisAdapter($redisConnection, '', 60);
        $this->httpClient = $httpClient;
        $this->twitterOptions = $twitterOptions;
        $this->options = $options;
    }

    public function handle(GetMessagesCommand $getMessagesCommand): array
    {
        //if (!$this->cache->getItem($username."-".$numberOfMessages)->isHit()) {
        $query = "statuses/user_timeline.json?screen_name=".$getMessagesCommand->getUsername()."&count=".$getMessagesCommand->getNumberOfMessages().
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

        return $messages = $this->extractMessagesFromResponse($jsonTwitterResponse);
    }

    /**
     * Get an array of Messages from the Twitter response
     *
     * @param array $twitterMessages
     * @return Message[]
     */
    private function extractMessagesFromResponse(array $twitterMessages) : array
    {
        $messages = [];
        foreach ($twitterMessages as $completeMessageInfo) {
            $text = $completeMessageInfo['full_text'];

            if (array_key_exists('text_modifiers', $this->options)) {
                foreach ($this->options['text_modifiers'] as $modifier) {
                    $text = $modifier($text);
                }
            }
            $message = new Message($completeMessageInfo['id'],$text);
            $messages[] = $message;
        }

        return $messages;
    }
}