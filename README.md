# APItest

APItest is a RESTful API which connects with Twitter in order to retrieve the last tweets of a user.
It is written using PHP 7.1 and Symfony, and virtualized with Docker. It also uses Redis as a cache system.

Endpoint example: api/apitest/twitter/user/darkkz/messages/5

## Firstly, the bad news

* I haven't used DDD. This is due to two reasons: The app don't need persistence, so it would have not
much sense to split the infrastructure layer. The other reason is I'm still learning it, but I haven't seen
enough complex examples to develop it properly.

* I've used Symfony 2.8 methods in the Symfony 3.3 kernel. This is because I cloned the the standard-edition
from GitHub but I forgot to change the branch.

## Introduction to the technologies

I have used PHP7's type declarations for both arguments and returns.

I have written doc-blocks for PHPDoc with the definition, arguments and return of the methods.

I have developed a Docker stack with *nginx*, *PHP-FPM* and *Redis*. I have also built a multi-container
manager using Docker-Compose.

The bundle is a microservice: it has its own parameters, service declarations, factories, etc... with the
only exception of the router, which I consider it should be managed as a global definition.

I have used a Factory to generate MessageService instances depending on the request, so the microservice
could in a future have different implementations of the interface *MessageServiceInterface* and return
the last Facebook, Linkedin... posts.

I have used Redis for caching the calls. I just made one rule: if the same request was called less than
one minute ago, it returns the cached response. A cool additional rule could be caching by the tweet ID
and use the twitter API parameter *since_id* to retrieve only the possible new tweets.

## Problems I've run into

I have lost time with the Symfony's version issue. I tried to refactor everything but later I chose to
use the time implementing the cache instead.

I also had some problems while caching, because I was trying to cache the entire Guzzle\Response object
but then it wasn't recognized as is, so I had to refactor a bit the methods and cache just the messages.

I made the connection to Redis manually in order to inject it via container later, but I had some
problems because it's created by an adapter method so I ran out of time and I had to leave it in the Service.
Also the route to the Redis service is hardcoded (shame! shame!), it shuold be in the parameters file.

## Time I've spent completing this test

I've spent 5 evening/nights, I would say around 20-25 hours.
