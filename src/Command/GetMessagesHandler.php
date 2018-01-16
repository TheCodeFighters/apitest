<?php
namespace App\Command;
use App\Command\GetMessagesCommand;
use App\Entity\Message;
use App\Service\TwitterMessageService;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use App\Event\TwitterGetMessagesEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\Event\EventListener\TwitterEventListener;
use Psr\Log\LoggerInterface;

//for extraConfigs
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class GetMessagesHandler
{


    private $twitterMessageService;
    private $cache;
    private $dispatcher;

    public function __construct(AdapterInterface $cache, TwitterMessageService $twitterMessageService,LoggerInterface $logger)
    {
        $this->twitterMessageService = $twitterMessageService;
        $this->cache = $cache;
        $this->dispatcher = new EventDispatcher();
        $twitterListener = new TwitterEventListener($logger);
        $this->dispatcher->addListener('app.get_messages.action', array($twitterListener, 'onGetMessagesAction'));
        $this->extraConfigs();
    }


    private function extraConfigs(){
        $containerBuilder = new ContainerBuilder(new ParameterBag());
        // register the compiler pass that handles the 'kernel.event_listener'
        // and 'kernel.event_subscriber' service tags
        $containerBuilder->addCompilerPass(new RegisterListenersPass());

        $containerBuilder->register('event_dispatcher', EventDispatcher::class);

        // register an event listener
        $containerBuilder->register('listener_service_id', \TwitterEventListener::class)
            ->addTag('kernel.event_listener', array(
                'event' => 'twitter.get_messages_request',
                'method' => 'onGetMessagesAction',
            ));
    }

    /**
     * @param \App\Command\GetMessagesCommand $getMessagesCommand
     * @return Message[]
     */
    public function handle(GetMessagesCommand $getMessagesCommand): array
    {

        if (!$this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages())->isHit()) {
            $responseJson = $this->twitterMessageService->getMessagesByUsernameAndNumberOfMessages($getMessagesCommand->getUsername(),$getMessagesCommand->getNumberOfMessages());
            $messageCached = $this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages());
            $messageCached->set($responseJson);
            $this->cache->save($messageCached);
        } else {
            $responseJson = $this->cache->getItem($getMessagesCommand->getUsername()."-".$getMessagesCommand->getNumberOfMessages())->get();
        }

        $messages = [];
        foreach ($responseJson as $completeMessageInfo) {
            $text = $completeMessageInfo['full_text'];
            $message = new Message($completeMessageInfo['id'],$text);
            $messages[] = $message;
        }
        // creo el evento y lo sirvo
        $event = new TwitterGetMessagesEvent($getMessagesCommand);
        $this->dispatcher->dispatch(TwitterGetMessagesEvent::NAME, $event);

        return $messages;
    }

}