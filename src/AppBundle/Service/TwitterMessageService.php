<?php

namespace AppBundle\Service;

use AppBundle\Entity\Message;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Cache\Adapter\RedisAdapter;

final class TwitterMessageService implements MessageServiceInterface
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
        $redisConnection = RedisAdapter::createConnection('redis://redis:6379');
        $this->cache = new RedisAdapter($redisConnection, '', 60);
        $this->httpClient = $httpClient;
        $this->twitterOptions = $twitterOptions;
        $this->options = $options;
    }

    /**
     * Get the last n messages posted by an user, using the cache or caching the result from twitter.
     * Only the text of the twits are returned. General and Twitter-specific options are applied.
     *
     * @param string $username
     * @param int $numberOfMessages
     * @return array
     */
    public function getUserMessages(string $username, int $numberOfMessages) : array
    {
        if (!$this->cache->getItem($username."-".$numberOfMessages)->isHit()) {
            $query = "statuses/user_timeline.json?screen_name=$username&count=$numberOfMessages".
                "&exclude_replies=".$this->twitterOptions['user_timeline']['exclude_replies'].
                "&include_rts=".$this->twitterOptions['user_timeline']['include_rts'].
                "&tweet_mode=extended";
            $twitterResponse = $this->httpClient->get($query);
            $jsonTwitterResponse = $twitterResponse->json();

            $messageCached = $this->cache->getItem($username."-".$numberOfMessages);
            $messageCached->set($jsonTwitterResponse);
            $this->cache->save($messageCached);
        } else {
            $jsonTwitterResponse = $this->cache->getItem($username."-".$numberOfMessages)->get();
        }

        $messages = $this->extractMessagesFromResponse($jsonTwitterResponse);
        return $this->extractTextFromMessages($messages);
    }

    /**
     * Get an array with the text contents of the given Messages
     *
     * @param Message[] $messages
     * @return array
     */
    private function extractTextFromMessages(array $messages): array
    {
        $texts = [];
        foreach ($messages as $message) {
            $texts[] = $message->getText();
        }

        return $texts;
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
            $message = new Message();
            $message->setId($completeMessageInfo['id']);
            $text = $completeMessageInfo['full_text'];

            if (array_key_exists('text_modifiers', $this->options)) {
                foreach ($this->options['text_modifiers'] as $modifier) {
                    $text = $modifier($text);
                }
            }

            $message->setText($text);
            $messages[] = $message;
        }

        return $messages;
    }

}